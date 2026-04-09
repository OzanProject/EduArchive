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
use App\Models\LearningActivity;
use App\Models\InfrastructureRequest;
use Barryvdh\DomPDF\Facade\Pdf;

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

    $tenants = Tenant::all(); // for the select dropdown

    $query = Tenant::query();

    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('nama_sekolah', 'like', "%{$search}%")
          ->orWhere('npsn', 'like', "%{$search}%");
      });
    }

    if ($request->filled('jenjang')) {
      $query->where('jenjang', $request->jenjang);
    }

    $allTenants = $query->orderBy('nama_sekolah', 'asc')->paginate(10)->appends($request->query());

    return view('backend.superadmin.reports.index', compact('tenants', 'allTenants'));
  }

  public function show($tenantId)
  {
    if ($tenantId === 'all') {
      $stats = $this->getComparativeStats();
      return view('backend.superadmin.reports.compare', compact('stats'));
    } else {
      $tenant = Tenant::findOrFail($tenantId);
      $stats = $this->getStats($tenant);
      return view('backend.superadmin.reports.show', compact('tenant', 'stats'));
    }
  }

  public function pdfExport($tenantId)
  {
    if ($tenantId === 'all') {
      $stats = $this->getComparativeStats();
      $pdf = Pdf::loadView('backend.superadmin.reports.compare_pdf', compact('stats'))
        ->setPaper('a4', 'landscape');
      
      return $pdf->download('Laporan_Perbandingan_Lembaga_' . date('Ymd_His') . '.pdf');
    } else {
      $tenant = Tenant::findOrFail($tenantId);
      $stats = $this->getStats($tenant);
      
      $pdf = Pdf::loadView('backend.superadmin.reports.pdf', compact('tenant', 'stats'))
        ->setPaper('a4', 'portrait');

      $filename = 'Laporan_' . $tenant->npsn . '_' . date('Ymd_His') . '.pdf';
      return $pdf->download($filename);
    }
  }

  private function getStats($tenant = null)
  {
    if ($tenant) {
      tenancy()->initialize($tenant);
    }

    $tenantScope = \Stancl\Tenancy\Database\TenantScope::class;
    
    // Helper to query with or without tenant scope
    $query = function($modelClass) use ($tenant, $tenantScope) {
      return $tenant ? $modelClass::query() : $modelClass::withoutGlobalScope($tenantScope);
    };

    $activeStudents = $query(Student::class)->whereIn('status_kelulusan', ['Aktif', 'aktif'])->get();

    $stats = [
      'students' => [
        'total' => $query(Student::class)->count(),
        'active' => $activeStudents->count(),
        'graduated' => $query(Student::class)->whereIn('status_kelulusan', ['Lulus', 'lulus'])->count(),
        'others' => $query(Student::class)->whereNotIn('status_kelulusan', ['Aktif', 'aktif', 'Lulus', 'lulus'])->count(),
      ],
      'gender' => [
        'L' => $activeStudents->where('gender', 'L')->count(),
        'P' => $activeStudents->where('gender', 'P')->count(),
      ],
      'classrooms' => $query(Classroom::class)->count(),
      'classroom_stats' => $tenant ? $query(Classroom::class)->withCount([
        'students as total' => function ($q) {
          $q->whereIn('status_kelulusan', ['Aktif', 'aktif']);
        },
        'students as male' => function ($q) {
          $q->whereIn('status_kelulusan', ['Aktif', 'aktif'])->where('gender', 'L');
        },
        'students as female' => function ($q) {
          $q->whereIn('status_kelulusan', ['Aktif', 'aktif'])->where('gender', 'P');
        }
      ])->get()->mapWithKeys(function ($item) {
        return [
          $item->nama_kelas => [
            'total' => $item->total,
            'male' => $item->male,
            'female' => $item->female,
          ]
        ];
      })->toArray() : [], // Leave empty for ALL to avoid too many duplicate classes
      'teachers' => [
        'total' => $query(Teacher::class)->count(),
        'pns' => $query(Teacher::class)->whereIn('status_kepegawaian', ['PNS', 'pns'])->count(),
        'pppk' => $query(Teacher::class)->whereIn('status_kepegawaian', ['PPPK', 'pppk'])->count(),
        'honorer' => $query(Teacher::class)->whereIn('status_kepegawaian', ['Honorer', 'honorer'])->count(),
      ],
      'documents' => $query(Document::class)->count(),
      'school_documents' => $query(SchoolDocument::class)->count(),
      'learning_activities' => [
        'total' => $query(LearningActivity::class)->count(),
        'pending' => $query(LearningActivity::class)->where('status', 'pending')->count(),
        'approved' => $query(LearningActivity::class)->where('status', 'approved')->count(),
        'rejected' => $query(LearningActivity::class)->where('status', 'rejected')->count(),
      ],
      'infrastructure' => [
        'total' => $query(InfrastructureRequest::class)->count(),
        'rkb' => $query(InfrastructureRequest::class)->where('type', 'RKB')->count(),
        'rehab' => $query(InfrastructureRequest::class)->where('type', 'REHAB')->count(),
        'other' => $query(InfrastructureRequest::class)->whereNotIn('type', ['RKB', 'REHAB'])->count(),
      ],
      'pip' => [
        'total' => $query(\App\Models\PipData::class)->count(),
        'disetujui' => $query(\App\Models\PipData::class)->where('status', 'disetujui')->count(),
      ],
    ];

    $ageStats = ['< 7' => 0, '7-12' => 0, '13-15' => 0, '16-18' => 0, '> 18' => 0, 'Kosong' => 0];
    $studentDetails = [];
    
    foreach ($activeStudents as $student) {
      $age = null;
      if (!$student->birth_date) {
        $ageStats['Kosong']++;
      } else {
        $age = \Carbon\Carbon::parse($student->birth_date)->age;
        if ($age < 7) {
          $ageStats['< 7']++;
        } elseif ($age <= 12) {
          $ageStats['7-12']++;
        } elseif ($age <= 15) {
          $ageStats['13-15']++;
        } elseif ($age <= 18) {
          $ageStats['16-18']++;
        } else {
          $ageStats['> 18']++;
        }
      }
      
      // Optionally restrict details rendering for ALL to avoid freezing the system if thousands of kids
      if ($tenant) {
        $studentDetails[] = [
          'nama' => $student->nama,
          'kelas' => $student->classroom ? $student->classroom->nama_kelas : ($student->kelas ?? '-'),
          'gender' => $student->gender,
          'age' => $age,
          'birth_date' => $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-'
        ];
      }
    }
    
    $stats['age_stats'] = $ageStats;
    $stats['student_details'] = $studentDetails;

    if ($tenant) {
      tenancy()->end();
    }

    return $stats;
  }

  private function getComparativeStats()
  {
    $tenants = Tenant::where('status_aktif', 1)->orderBy('nama_sekolah')->get();
    $tenantScope = \Stancl\Tenancy\Database\TenantScope::class;
    
    // Helper mapper untuk mengambil count di group by tenant_id
    $getCountsByTenant = function($modelClass, $condition = null) use ($tenantScope) {
        $q = $modelClass::withoutGlobalScope($tenantScope)
            ->selectRaw('tenant_id, count(*) as total')
            ->groupBy('tenant_id');
            
        if ($condition) {
            $condition($q);
        }
        
        return $q->pluck('total', 'tenant_id');
    };

    $activeStudents = $getCountsByTenant(Student::class, function($q) {
        $q->whereIn('status_kelulusan', ['Aktif', 'aktif']);
    });
    
    $graduatedStudents = $getCountsByTenant(Student::class, function($q) {
        $q->whereIn('status_kelulusan', ['Lulus', 'lulus']);
    });

    $teachers = $getCountsByTenant(Teacher::class);
    $infrastructures = $getCountsByTenant(InfrastructureRequest::class, function($q){
        $q->where('status', 'pending');
    });

    $learningActivities = $getCountsByTenant(LearningActivity::class, function($q){
        $q->whereIn('status', ['approved', 'pending']);
    });

    $pipStats = $getCountsByTenant(\App\Models\PipData::class);

    $results = [];
    foreach($tenants as $t) {
        $results[] = [
            'id' => $t->id,
            'nama_sekolah' => $t->nama_sekolah ?? $t->id,
            'npsn' => $t->npsn ?? '-',
            'jenjang' => $t->jenjang ?? '-',
            'active_students' => $activeStudents[$t->id] ?? 0,
            'graduated_students' => $graduatedStudents[$t->id] ?? 0,
            'total_teachers' => $teachers[$t->id] ?? 0,
            'pending_infrastructure' => $infrastructures[$t->id] ?? 0,
            'total_learning' => $learningActivities[$t->id] ?? 0,
            'total_pip' => $pipStats[$t->id] ?? 0,
        ];
    }
    
    return collect($results);
  }

}
