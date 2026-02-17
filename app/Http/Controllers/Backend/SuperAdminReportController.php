<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Document;
use App\Models\SchoolDocument;

class SuperAdminReportController extends Controller
{
  public function index(Request $request)
  {
    if ($request->has('tenant_id') && $request->tenant_id) {
      return redirect()->route('superadmin.reports.show', $request->tenant_id);
    }

    if ($request->has('npsn') && $request->npsn) {
      $npsn = trim($request->npsn);
      $tenant = Tenant::where('npsn', $npsn)->first();
      if ($tenant) {
        return redirect()->route('superadmin.reports.show', $tenant->id);
      }
      return redirect()->back()->with('error', 'Sekolah dengan NPSN ' . $npsn . ' tidak ditemukan.');
    }

    $tenants = Tenant::all();
    return view('backend.superadmin.reports.index', compact('tenants'));
  }

  public function show($tenantId)
  {
    $tenant = Tenant::findOrFail($tenantId);

    // Initialize tenancy to scope queries to this tenant
    tenancy()->initialize($tenant);

    // Collect Statistics (Same logic as Tenant ReportController)
    $stats = [
      'students' => [
        'total' => Student::count(),
        'active' => Student::whereIn('status_kelulusan', ['Aktif', 'aktif'])->count(),
        'graduated' => Student::whereIn('status_kelulusan', ['Lulus', 'lulus'])->count(),
        'others' => Student::whereNotIn('status_kelulusan', ['Aktif', 'aktif', 'Lulus', 'lulus'])->count(),
      ],
      'teachers' => [
        'total' => Teacher::count(),
        'pns' => Teacher::whereIn('status_kepegawaian', ['PNS', 'pns'])->count(),
        'pppk' => Teacher::whereIn('status_kepegawaian', ['PPPK', 'pppk'])->count(),
        'honorer' => Teacher::whereIn('status_kepegawaian', ['Honorer', 'honorer'])->count(),
      ],
      'classrooms' => Classroom::count(),
      'documents' => Document::count(),
      'school_documents' => SchoolDocument::count(),
    ];

    // We don't necessarily need to end tenancy if we are just returning a view, 
    // but it's safer if there are other operations. 
    // However, for single-db, the scope persists. 
    // Let's keep it initialized so the view can potentially access other tenant-scoped things if needed.
    // Or we can end it. Ideally, Super Admin view shouldn't depend on tenant context globally.
    tenancy()->end();

    return view('backend.superadmin.reports.show', compact('tenant', 'stats'));
  }
}
