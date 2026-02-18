<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Student;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MonitoringController extends Controller
{
  public function index(Request $request)
  {
    $query = Student::query();

    if ($request->filled('search')) {
      $search = trim($request->search);

      $query->where(function ($q) use ($search) {
        $q->where('nama', 'like', "%{$search}%")
          ->orWhere('nis', 'like', "%{$search}%");
      });
    }

    $allowedSort = ['nama', 'nis', 'created_at'];
    $sort = in_array($request->sort, $allowedSort) ? $request->sort : 'created_at';
    $direction = $request->direction === 'asc' ? 'asc' : 'desc';

    $query->orderBy($sort, $direction);

    $perPage = (int) $request->per_page;
    $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 10;

    $students = $query->paginate($perPage)->withQueryString();

    return view('students_detail.index', compact('students'));
  }

  public function showSchool(Request $request, $id)
  {
    $tenant = Tenant::findOrFail($id);

    $status = $request->get('status', 'aktif');
    $year = $request->get('year');

    $students = $tenant->run(function () use ($status, $year, $request) {

      $query = Student::query();

      if ($request->filled('table_search')) {
        $search = $request->table_search;

        $query->where(function ($q) use ($search) {
          $q->where('nama', 'like', "%{$search}%")
            ->orWhere('nisn', 'like', "%{$search}%");
        });
      }

      if ($status === 'lulus') {
        $query->where('status_kelulusan', 'lulus');

        if ($year) {
          $query->where('tahun_lulus', $year);
        }
      } else {
        $query->where('status_kelulusan', 'aktif');
      }

      return $query->latest()->get();
    });

    $graduation_years = $tenant->run(function () {
      return Student::where('status_kelulusan', 'lulus')
        ->select('tahun_lulus')
        ->distinct()
        ->orderBy('tahun_lulus', 'desc')
        ->pluck('tahun_lulus');
    });

    return view('backend.superadmin.monitoring.students', compact(
      'tenant',
      'students',
      'graduation_years'
    ));
  }

  public function showStudent(Request $request, $tenant_id, $id)
  {
    $tenant = Tenant::findOrFail($tenant_id);

    $student = $tenant->run(function () use ($id) {
      return Student::findOrFail($id);
    });

    // ✅ PAGINATION DOKUMEN (AMAN UNTUK HOSTING)
    $documents = $tenant->run(function () use ($id) {
      return Document::with('validator')
        ->where('student_id', $id)
        ->latest()
        ->paginate(10);
    });

    // ===============================
    // HITUNG COMPLETENESS (LOGIKA TETAP)
    // ===============================
    $required_types = DocumentType::where('is_required', true)
      ->where('is_active', true)
      ->pluck('name');

    $approved_docs = $tenant->run(function () use ($id) {
      return Document::where('student_id', $id)
        ->where('validation_status', 'approved')
        ->get();
    });

    $approved_types = $approved_docs->pluck('document_type')->toArray();

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
    $completeness = $total_required > 0
      ? min(100, round(($filled_count / $total_required) * 100))
      : 100;

    // ===============================
    // AUDIT LOG
    // ===============================
    $logs = AuditLog::where('tenant_id', $tenant_id)
      ->where('details', 'like', '%"student_id":' . $id . '%')
      ->with('user')
      ->latest()
      ->limit(10)
      ->get()
      ->map(function ($log) {
        $details = json_decode($log->details, true);

        return (object) [
          'user' => $log->user,
          'document_name' => $details['document_name'] ?? 'Unknown',
          'created_at' => $log->created_at,
          'action' => $log->action,
          'details' => $details
        ];
      });

    return view('backend.superadmin.monitoring.student_detail', compact(
      'tenant',
      'student',
      'documents',
      'completeness',
      'missing_docs',
      'logs'
    ));
  }

  public function viewDocument($tenant_id, $student_id, $document_id)
  {
    try {
      $tenant = Tenant::findOrFail($tenant_id);

      return $tenant->run(function () use ($document_id, $tenant_id, $student_id) {

        $document = Document::findOrFail($document_id);

        $disk = Storage::disk('public');

        if (!$disk->exists($document->file_path)) {
          abort(404, 'File tidak ditemukan.');
        }

        $fullPath = $disk->path($document->file_path);

        $this->logAction(
          $tenant_id,
          $student_id,
          'VIEW',
          Document::class,
          $document_id,
          [
            'document_name' => $document->document_type,
            'document_type' => $document->document_type
          ]
        );

        return response()->file($fullPath);
      });

    } catch (\Exception $e) {
      Log::error("ViewDocument error: " . $e->getMessage());
      abort(500, "Error membuka dokumen.");
    }
  }

  public function approveDocument($tenant_id, $student_id, $document_id)
  {
    try {
      $tenant = Tenant::findOrFail($tenant_id);

      $tenant->run(function () use ($document_id, $tenant_id, $student_id) {

        $document = Document::findOrFail($document_id);

        $document->update([
          'validation_status' => 'approved',
          'validated_by' => auth()->id(),
          'validated_at' => now(),
          'validation_notes' => null,
        ]);

        $this->logAction(
          $tenant_id,
          $student_id,
          'APPROVE',
          Document::class,
          $document_id,
          [
            'document_name' => $document->document_type,
            'status' => 'approved'
          ]
        );
      });

      return back()->with('success', 'Dokumen berhasil disetujui.');

    } catch (\Exception $e) {
      Log::error("Approve error: " . $e->getMessage());
      return back()->with('error', 'Gagal menyetujui dokumen.');
    }
  }

  public function rejectDocument(Request $request, $tenant_id, $student_id, $document_id)
  {
    $request->validate([
      'validation_notes' => 'required|string|min:10|max:500',
    ]);

    try {
      $tenant = Tenant::findOrFail($tenant_id);

      $tenant->run(function () use ($document_id, $request, $tenant_id, $student_id) {

        $document = Document::findOrFail($document_id);

        $document->update([
          'validation_status' => 'rejected',
          'validated_by' => auth()->id(),
          'validated_at' => now(),
          'validation_notes' => $request->validation_notes,
        ]);

        $this->logAction(
          $tenant_id,
          $student_id,
          'REJECT',
          Document::class,
          $document_id,
          [
            'document_name' => $document->document_type,
            'status' => 'rejected',
            'notes' => $request->validation_notes
          ]
        );
      });

      return back()->with('success', 'Dokumen ditolak.');

    } catch (\Exception $e) {
      Log::error("Reject error: " . $e->getMessage());
      return back()->with('error', 'Gagal menolak dokumen.');
    }
  }

  private function logAction($tenant_id, $student_id, $action, $target_type, $target_id, $details = [])
  {
    AuditLog::create([
      'user_id' => auth()->id(),
      'tenant_id' => $tenant_id,
      'action' => $action,
      'target_type' => $target_type,
      'target_id' => $target_id,
      'ip_address' => request()->ip(),
      'details' => json_encode(array_merge($details, [
        'student_id' => $student_id,
        'user_agent' => request()->userAgent()
      ])),
    ]);
  }
}