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
    $category = $request->input('category', 'students'); // students or graduates
    $age_filter = $request->input('age_filter');
    $per_page = $request->input('per_page', 10);
    $query = Tenant::query();

    if ($request->has('table_search') && $request->table_search != '') {
      $search = $request->table_search;
      $query->where([['npsn', 'like', "%{$search}%"]])
        ->orWhere([['nama_sekolah', 'like', "%{$search}%"]]);
    }

    $tenants = $query->paginate($per_page);
    $statusFilter = $category === 'graduates' ? 'lulus' : 'aktif';

    foreach ($tenants as $tenant) {
        $tenant->run(function () use ($tenant, $statusFilter, $age_filter) {
            $query = Student::where('status_kelulusan', $statusFilter);
            
            if ($age_filter == 'under_25') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 25');
            } elseif ($age_filter == 'over_25') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 25');
            }

            $tenant->stats_total = (clone $query)->count();
            $tenant->stats_l = (clone $query)->where('gender', 'L')->count();
            $tenant->stats_p = (clone $query)->where('gender', 'P')->count();
        });
    }

    return view('backend.superadmin.monitoring.index', compact('tenants', 'category', 'age_filter', 'per_page'));
  }

  public function showSchool(Request $request, $id)
  {
    $tenant = Tenant::findOrFail($id);

    $status = $request->input('status', 'aktif');
    $year = $request->input('year');
    $per_page = $request->input('per_page', 15);

    $students = $tenant->run(function () use ($status, $year, $per_page, $request) {

      $query = Student::query();

      if ($request->filled('table_search')) {
        $search = $request->table_search;

        $query->where(function ($q) use ($search) {
          $q->where([['nama', 'like', "%{$search}%"]])
            ->orWhere([['nisn', 'like', "%{$search}%"]]);
        });
      }

      if ($status === 'lulus') {
        $query->where(['status_kelulusan' => 'lulus']);

        if ($year) {
          $query->where(['tahun_lulus' => $year]);
        }
      } else {
        $query->where(['status_kelulusan' => 'aktif']);
      }

      // Filter usia berdasarkan birth_date
      $ageFilter = $request->input('age_filter');
      if ($ageFilter === 'under_25') {
        $cutoff = now()->subYears(25)->format('Y-m-d');
        $query->where([['birth_date', '>', $cutoff]]);
      } elseif ($ageFilter === 'over_25') {
        $cutoff = now()->subYears(25)->format('Y-m-d');
        $query->where([['birth_date', '<=', $cutoff]]);
      }

      return $query->latest()->paginate($per_page);
    });

    // Fix paginator URL — tenant->run() doesn't know the real HTTP path
    $students->withPath(route('superadmin.monitoring.school', $id));

    $graduation_years = $tenant->run(function () {
      return Student::where(['status_kelulusan' => 'lulus'])
        ->select('tahun_lulus')
        ->distinct()
        ->orderByRaw('tahun_lulus desc')
        ->pluck('tahun_lulus');
    });

    return view('backend.superadmin.monitoring.students', compact(
      'tenant',
      'students',
      'graduation_years',
      'per_page'
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
        ->where(['student_id' => $id])
        ->latest()
        ->paginate(10);
    });

    // ===============================
    // HITUNG COMPLETENESS (LOGIKA TETAP)
    // ===============================
    $required_types = DocumentType::where(['is_required' => true, 'is_active' => true])
      ->pluck('name');

    $approved_docs = $tenant->run(function () use ($id) {
      return Document::where(['student_id' => $id, 'validation_status' => 'approved'])
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
    $logs = AuditLog::where(['tenant_id' => $tenant_id])
      ->where(function ($q) use ($id) {
        $q->where('details', 'like', '%"student_id":"' . $id . '"%')
          ->orWhere('details', 'like', '%"student_id":' . $id . '%');
      })
      ->with('user')
      ->latest()
      ->limit(10)
      ->get()
      ->map(function ($log) {
        return (object) [
          'user' => $log->user,
          'document_name' => $log->details['document_name'] ?? 'Unknown',
          'created_at' => $log->created_at,
          'action' => $log->action,
          'details' => $log->details
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

  public function printAllRecap(Request $request)
  {
    $category = $request->input('category', 'students');
    $status = $category == 'graduates' ? 'lulus' : 'aktif';
    $age_filter = $request->input('age_filter');

    $query = Tenant::query();

    if ($request->has('table_search') && $request->table_search != '') {
      $search = $request->table_search;
      $query->where([['npsn', 'like', "%{$search}%"]])
        ->orWhere([['nama_sekolah', 'like', "%{$search}%"]]);
    }

    $tenants = $query->get();

    $recapData = [];
    foreach ($tenants as $tenant) {
      /** @var Tenant $tenant */
      $data = $tenant->run(function () use ($status, $age_filter) {
        $q = Student::with('documents');

        if ($status == 'lulus') {
          $q->where(['status_kelulusan' => 'lulus']);
        } else {
          $q->where(['status_kelulusan' => 'aktif']);
        }

        if ($age_filter === 'under_25') {
          $cutoff = now()->subYears(25)->format('Y-m-d');
          $q->where([['birth_date', '>', $cutoff]]);
        } elseif ($age_filter === 'over_25') {
          $cutoff = now()->subYears(25)->format('Y-m-d');
          $q->where([['birth_date', '<=', $cutoff]]);
        }

        return $q->orderByRaw('nama ASC')->get();
      });

      $recapData[] = [
        'tenant' => $tenant,
        'data' => $data
      ];
    }

    return view('backend.superadmin.monitoring.print_all_recap', compact('recapData', 'status', 'age_filter'));
  }

  public function printRecap(Request $request, $id)
  {
    $tenant = Tenant::findOrFail($id);
    $status = $request->input('status', 'aktif');
    $year = $request->input('year');
    $age_filter = $request->input('age_filter');

    $data = $tenant->run(function () use ($status, $year, $age_filter) {
      $query = Student::with('documents');

      if ($status == 'lulus') {
        $query->where(['status_kelulusan' => 'lulus']);
        if ($year) {
          $query->where(['tahun_lulus' => $year]);
        }
      } else {
        $query->where(['status_kelulusan' => 'aktif']);
      }

      // Filter usia berdasarkan birth_date (Cetak Rekap)
      if ($age_filter === 'under_25') {
        $cutoff = now()->subYears(25)->format('Y-m-d');
        $query->where([['birth_date', '>', $cutoff]]);
      } elseif ($age_filter === 'over_25') {
        $cutoff = now()->subYears(25)->format('Y-m-d');
        $query->where([['birth_date', '<=', $cutoff]]);
      }

      return $query->orderByRaw('nama ASC')->get();
    });

    return view('backend.superadmin.monitoring.print_recap', compact('tenant', 'data', 'status', 'year', 'age_filter'));
  }

  public function auditLogs()
  {
    $logs = AuditLog::with(['user', 'tenant'])->latest()->paginate(20);
    return view('backend.superadmin.monitoring.audit_logs', compact('logs'));
  }

  public function destroyAuditLog($id)
  {
    $log = AuditLog::findOrFail($id);
    $log->delete();

    return redirect()->back()->with('success', 'Log audit berhasil dihapus.');
  }

  public function bulkDestroyAuditLog(Request $request)
  {
    $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'integer|exists:audit_logs,id'
    ]);

    $ids = $request->input('ids');

    AuditLog::whereIn('id', $ids)->delete();

    return redirect()->back()->with('success', 'Log audit yang dipilih berhasil dihapus.');
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
      'details' => array_merge($details, [
        'student_id' => $student_id,
        'student_nisn' => request()->input('student_nisn', '-'),
        'student_nama' => request()->input('student_nama', 'Unknown'),
        'user_agent' => request()->userAgent()
      ]),
    ]);
  }
}