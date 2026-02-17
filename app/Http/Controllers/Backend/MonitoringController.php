<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
  /**
   * Menampilkan daftar sekolah untuk dipilih.
   */
  /**
   * Menampilkan daftar sekolah untuk dipilih.
   */
  public function index(Request $request)
  {
    $category = $request->get('category', 'students'); // students or graduates
    $query = Tenant::query();

    if ($request->has('table_search') && $request->table_search != '') {
      $search = $request->table_search;
      $query->where('npsn', 'like', "%{$search}%")
        ->orWhere('nama_sekolah', 'like', "%{$search}%");
    }

    $tenants = $query->paginate(10);
    return view('backend.superadmin.monitoring.index', compact('tenants', 'category'));
  }

  /**
   * Menampilkan daftar siswa dari sekolah tertentu.
   */
  public function showSchool(Request $request, $id)
  {
    $tenant = Tenant::findOrFail($id);

    $status = $request->get('status', 'aktif'); // Default: aktif
    $year = $request->get('year');

    // Menggunakan method run() untuk switch context ke database tenant
    $students = $tenant->run(function () use ($status, $year, $request) {
      $query = \App\Models\Student::with('documents');

      if ($request->has('table_search') && $request->table_search != '') {
        $search = $request->table_search;
        $query->where(function ($q) use ($search) {
          $q->where('nama', 'like', "%{$search}%")
            ->orWhere('nisn', 'like', "%{$search}%");
        });
      }

      if ($status == 'lulus') {
        $query->where('status_kelulusan', 'lulus');
        if ($year) {
          $query->where('tahun_lulus', $year);
        }
      } else {
        $query->where('status_kelulusan', 'aktif');
      }

      // $query->whereNotNull('nisn')->where('nisn', '!=', ''); // Commented out to allow students without NISN

      return $query->latest()->get();
    });

    // Get unique graduation years for filter dropdown
    $graduation_years = $tenant->run(function () {
      return \App\Models\Student::where('status_kelulusan', 'lulus')
        ->select('tahun_lulus')
        ->distinct()
        ->orderBy('tahun_lulus', 'desc')
        ->pluck('tahun_lulus');
    });

    return view('backend.superadmin.monitoring.students', compact('tenant', 'students', 'graduation_years'));
  }

  public function showStudent($tenant_id, $id)
  {
    $tenant = Tenant::findOrFail($tenant_id);

    // Fetch student detail inside tenant context
    $student = $tenant->run(function () use ($id) {
      return \App\Models\Student::with('documents')->findOrFail($id);
    });

    // Calculate Completeness based on DocumentType and validation status
    $required_types = \App\Models\DocumentType::where('is_required', true)->where('is_active', true)->pluck('name');

    // Only count APPROVED documents
    $approved_docs = $student->documents->where('validation_status', 'approved');
    $approved_types = $approved_docs->pluck('document_type')->toArray(); // Fixed: use document_type field


    // Calculate filled count and missing docs
    $filled_count = 0;
    $missing_docs = [];

    foreach ($required_types as $type) {
      if (in_array($type, $approved_types)) {
        $filled_count++;
      } else {
        $missing_docs[] = $type;
      }
    }

    $total_required = $required_types->count();
    $completeness = $total_required > 0 ? min(100, round(($filled_count / $total_required) * 100)) : 100;

    // Fetch Activity Logs for this student
    $logs = \App\Models\AuditLog::where('tenant_id', $tenant_id)
      ->where(function ($q) use ($id) {
        $q->where('details', 'like', '%"student_id":"' . $id . '"%')
          ->orWhere('details', 'like', '%"student_id":' . $id . '%');
      })
      ->with('user')
      ->latest()
      ->limit(10)
      ->get();

    // Map logs to expected format for view
    $formatted_logs = $logs->map(function ($log) {
      $details = json_decode($log->details, true);
      return (object) [
        'user' => $log->user,
        'document_name' => $details['document_name'] ?? 'Unknown Document',
        'created_at' => $log->created_at,
        'action' => $log->action,
        'details' => $details
      ];
    });

    return view('backend.superadmin.monitoring.student_detail', compact('tenant', 'student', 'completeness', 'missing_docs'), ['logs' => $formatted_logs]);
  }

  public function logAccess(Request $request, $tenant_id, $id, $document_id)
  {
    $tenant = \App\Models\Tenant::findOrFail($tenant_id);

    // 1. Cari data dokumen di dalam tenant
    $document = $tenant->run(function () use ($document_id) {
      return \App\Models\Document::findOrFail($document_id);
    });

    // 2. Catat Log Akses di Database User (Central)
    \App\Models\AuditLog::create([
      'user_id' => auth()->id(),
      'tenant_id' => $tenant_id,
      'action' => 'DOCUMENT_ACCESS_OLD', // Legacy access for now
      'target_type' => \App\Models\Document::class,
      'target_id' => $document_id,
      'ip_address' => $request->ip(),
      'details' => json_encode([
        'student_id' => $id,
        'student_nisn' => $request->input('student_nisn', '-'),
        'document_name' => $document->jenis_dokumen . ' (' . $document->file_path . ')',
        'user_agent' => $request->userAgent()
      ]),
    ]);

    // 3. Redirect to view document
    return redirect()->route('superadmin.monitoring.view_document', [
      'tenant_id' => $tenant_id,
      'id' => $id,
      'document_id' => $document_id
    ]);
  }


  /**
   * View/Download document - uses Storage facade
   */
  public function viewDocument($tenant_id, $id, $document_id)
  {
    try {
      $tenant = \App\Models\Tenant::findOrFail($tenant_id);

      // Execute EVERYTHING inside tenant context to ensure Storage::disk('public') 
      // resolves to the correct tenant storage path.
      return $tenant->run(function () use ($document_id, $tenant_id, $id) {
        $document = \App\Models\Document::findOrFail($document_id);

        // Storage disk is now tenant-aware because we're inside tenant->run()
        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        if (!$disk->exists($document->file_path)) {
          // Debug info if still not found
          $diskRoot = $disk->path('');
          \Log::error("File not found in tenant storage. Root: {$diskRoot}, Path: {$document->file_path}");
          abort(404, 'File not found in tenant storage: ' . $document->file_path);
        }

        // Return file response through tenant-aware Storage
        $response = $disk->response($document->file_path);

        // Log access after successful response generation
        $this->logAction($tenant_id, $id, 'VIEW', \App\Models\Document::class, $document_id, [
          'document_name' => $document->document_type . ' (' . basename($document->file_path) . ')',
          'document_type' => $document->document_type
        ]);

        return $response;
      });

    } catch (\Exception $e) {
      \Log::error("ViewDocument error: " . $e->getMessage());
      abort(500, "Error: " . $e->getMessage());
    }
  }


  public function printRecap(Request $request, $id)
  {
    $tenant = Tenant::findOrFail($id);
    $status = $request->get('status', 'aktif');
    $year = $request->get('year');

    $data = $tenant->run(function () use ($status, $year) {
      $query = \App\Models\Student::with('documents');

      if ($status == 'lulus') {
        $query->where('status_kelulusan', 'lulus');
        if ($year) {
          $query->where('tahun_lulus', $year);
        }
      } else {
        $query->where('status_kelulusan', 'aktif');
      }

      return $query->oldest('nama')->get();
    });

    return view('backend.superadmin.monitoring.print_recap', compact('tenant', 'data', 'status', 'year'));
  }

  public function auditLogs()
  {
    $logs = \App\Models\AuditLog::with('user')->latest()->paginate(20);
    return view('backend.superadmin.monitoring.audit_logs', compact('logs'));
  }

  public function destroyAuditLog($id)
  {
    $log = \App\Models\AuditLog::findOrFail($id);
    $log->delete();

    return redirect()->back()->with('success', 'Log audit berhasil dihapus.');
  }

  /**
   * Approve a student document
   */
  public function approveDocument($tenant_id, $student_id, $document_id)
  {
    try {
      $tenant = Tenant::findOrFail($tenant_id);

      $tenant->run(function () use ($document_id, $tenant_id, $student_id) {
        $document = \App\Models\Document::findOrFail($document_id);
        $document->update([
          'validation_status' => 'approved',
          'validated_by' => auth()->id(),
          'validated_at' => now(), // Correctly uses Asia/Jakarta now
          'validation_notes' => null,
        ]);

        // Log Approval - Wrap in secondary try-catch to not break main action if log fails
        try {
          $this->logAction($tenant_id, $student_id, 'APPROVE', \App\Models\Document::class, $document_id, [
            'document_name' => $document->document_type,
            'status' => 'approved'
          ]);
        } catch (\Exception $logEx) {
          \Log::error("Failed to log approval: " . $logEx->getMessage());
        }
      });

      return redirect()->back()->with('success', 'Dokumen berhasil disetujui.');
    } catch (\Exception $e) {
      \Log::error("Error in approveDocument: " . $e->getMessage());
      return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui dokumen: ' . $e->getMessage());
    }
  }

  /**
   * Reject a student document
   */
  public function rejectDocument(Request $request, $tenant_id, $student_id, $document_id)
  {
    try {
      $request->validate([
        'validation_notes' => 'required|string|max:500',
      ]);

      $tenant = Tenant::findOrFail($tenant_id);

      $tenant->run(function () use ($document_id, $request, $tenant_id, $student_id) {
        $document = \App\Models\Document::findOrFail($document_id);
        $document->update([
          'validation_status' => 'rejected',
          'validated_by' => auth()->id(),
          'validated_at' => now(), // Correctly uses Asia/Jakarta now
          'validation_notes' => $request->validation_notes,
        ]);

        // Log Rejection - Wrap in secondary try-catch
        try {
          $this->logAction($tenant_id, $student_id, 'REJECT', \App\Models\Document::class, $document_id, [
            'document_name' => $document->document_type,
            'status' => 'rejected',
            'notes' => $request->validation_notes
          ]);
        } catch (\Exception $logEx) {
          \Log::error("Failed to log rejection: " . $logEx->getMessage());
        }
      });

      return redirect()->back()->with('success', 'Dokumen ditolak dengan catatan.');
    } catch (\Exception $e) {
      \Log::error("Error in rejectDocument: " . $e->getMessage());
      return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak dokumen: ' . $e->getMessage());
    }
  }

  /**
   * Private helper to log monitoring actions
   */
  private function logAction($tenant_id, $student_id, $action, $target_type, $target_id, $details = [])
  {
    // Ensure we have student context for the details (needed for activity log display)
    $tenant = \App\Models\Tenant::find($tenant_id);
    if (!$tenant)
      return;

    $student = $tenant->run(function () use ($student_id) {
      return \App\Models\Student::find($student_id);
    });

    \App\Models\AuditLog::create([
      'user_id' => auth()->id(),
      'tenant_id' => $tenant_id,
      'action' => $action,
      'target_type' => $target_type,
      'target_id' => $target_id,
      'ip_address' => request()->ip(),
      'details' => json_encode(array_merge($details, [
        'student_id' => $student_id,
        'student_nisn' => $student->nisn ?? '-',
        'student_nama' => $student->nama ?? 'Unknown',
        'user_agent' => request()->userAgent()
      ])),
    ]);
  }
}
