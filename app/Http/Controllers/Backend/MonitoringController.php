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

    // Calculate Completeness based on DocumentType
    $required_types = \App\Models\DocumentType::where('is_required', true)->where('is_active', true)->pluck('name');
    $uploaded_types = $student->documents->pluck('jenis_dokumen')->toArray();

    // Normalize logic: check if required type exists in uploaded types
    $filled_count = 0;
    $missing_docs = [];

    foreach ($required_types as $type) {
      // Case-insensitive check or direct match? Let's assume direct match first, or fuzzy if needed.
      // But usually 'jenis_dokumen' should match 'DocumentType name'.
      if (in_array($type, $uploaded_types)) {
        $filled_count++;
      } else {
        $missing_docs[] = $type;
      }
    }

    $total_required = $required_types->count();
    $completeness = $total_required > 0 ? min(100, round(($filled_count / $total_required) * 100)) : 100;

    // Fetch Activity Logs for this student
    $logs = \App\Models\AuditLog::where('tenant_id', $tenant_id)
      ->where('details', 'like', '%"student_id":"' . $id . '"%') // Fix: Quote the ID if stored as string in JSON, or handle int.
      // Better Query:
      ->where(function ($q) use ($id, $student) {
        $q->where('details', 'like', '%"student_id":"' . $id . '"%')
          ->orWhere('details', 'like', '%"student_id":' . $id . '%') // Handle both number/string json
          ->orWhere('details', 'like', '%"student_nisn":"' . ($student->nisn ?? 'UNKNOWN') . '"%');
      })
      ->with('user')
      ->latest()
      ->limit(10) // Increase limit
      ->get();

    // Map logs to expected format for view (if needed, or update view)
    $formatted_logs = $logs->map(function ($log) {
      $details = json_decode($log->details, true);
      return (object) [
        'user' => $log->user,
        'document_name' => $details['document_name'] ?? 'Unknown Document',
        'created_at' => $log->created_at,
        'action' => $log->action // Pass action
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

    // 3. Return File (Simulasi atau Real)
    // Return mock PDF preview
    return $this->returnMockFile($document);
  }

  public function viewDocument($tenant_id, $id, $document_id)
  {
    $tenant = \App\Models\Tenant::findOrFail($tenant_id);
    $document = $tenant->run(function () use ($document_id) {
      return \App\Models\Document::findOrFail($document_id);
    });

    // Optional: Log 'Viewed' event if strict tracking is needed

    return $this->returnMockFile($document);
  }

  private function returnMockFile($document)
  {
    return response("
        <html>
        <head><title>Document Preview</title></head>
        <body style='text-align:center; padding-top:50px; font-family:sans-serif;'>
            <h1>Mock Document Preview</h1>
            <p><strong>Jenis Dokumen:</strong> {$document->jenis_dokumen}</p>
            <p><strong>File Path:</strong> {$document->file_path}</p>
            <hr>
            <p style='color:green;'>DOKUMEN DIAKSES</p>
            <p><em>(Dalam aplikasi production, ini akan meredirect ke file PDF asli atau force download)</em></p>
            <button onclick='window.close()'>Tutup</button>
        </body>
        </html>
    ");
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
}
