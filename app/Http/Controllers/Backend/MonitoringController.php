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

      $query->whereNotNull('nisn')->where('nisn', '!=', '');

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

  public function showStudent($tenant_id, $nisn)
  {
    $tenant = Tenant::findOrFail($tenant_id);

    // Fetch student detail inside tenant context
    $student = $tenant->run(function () use ($nisn) {
      return \App\Models\Student::where('nisn', $nisn)->with('documents')->firstOrFail();
    });

    // Calculate Competeness
    $required_docs = ['Ijazah', 'Transkrip Nilai', 'Kartu Keluarga', 'Akte Kelahiran']; // Example required list
    $uploaded_types = $student->documents->pluck('jenis_dokumen')->toArray();
    $filled_count = 0;
    foreach ($required_docs as $req) {
      // Simple fuzzy check or strict check. Let's do strict for now or based on existing types
      // For demonstration, we count how many documents are uploaded vs a fixed number (e.g., 5)
      // Or better: Count verified documents
    }
    // Simplification: 80% if Ijazah exists, etc.
    // Let's just count total uploaded vs target (say 5)
    $completeness = min(100, round(($student->documents->count() / 5) * 100));

    // Fetch Activity Logs for this student
    $logs = \App\Models\AuditLog::where('tenant_id', $tenant_id)
      ->where('details', 'like', '%"student_nisn":"' . $nisn . '"%') // Simple JSON search
      ->with('user')
      ->latest()
      ->limit(5)
      ->get();

    // Map logs to expected format for view (if needed, or update view)
    $formatted_logs = $logs->map(function ($log) {
      $details = json_decode($log->details, true);
      return (object) [
        'user' => $log->user,
        'document_name' => $details['document_name'] ?? 'Unknown Document',
        'created_at' => $log->created_at
      ];
    });

    return view('backend.superadmin.monitoring.student_detail', compact('tenant', 'student', 'completeness'), ['logs' => $formatted_logs]);
  }

  public function logAccess(Request $request, $tenant_id, $nisn, $document_id)
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
        'student_nisn' => $nisn,
        'document_name' => $document->jenis_dokumen . ' (' . $document->file_path . ')',
        'user_agent' => $request->userAgent()
      ]),
    ]);

    // 3. Return File (Simulasi atau Real)
    // Return mock PDF preview
    return response("
        <html>
        <head><title>Document Preview</title></head>
        <body style='text-align:center; padding-top:50px; font-family:sans-serif;'>
            <h1>Mock Document Preview</h1>
            <p><strong>Jenis Dokumen:</strong> {$document->jenis_dokumen}</p>
            <p><strong>File Path:</strong> {$document->file_path}</p>
            <hr>
            <p style='color:green;'>AKSES TERCATAT DI AUDIT LOG</p>
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
