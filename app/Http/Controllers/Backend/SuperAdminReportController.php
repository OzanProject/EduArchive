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
      'gender' => [
        'L' => Student::where('gender', 'L')->count(),
        'P' => Student::where('gender', 'P')->count(),
      ],
      'classrooms' => Classroom::count(),
      'classroom_stats' => Classroom::withCount([
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
      })->toArray(),
      'teachers' => [
        'total' => Teacher::count(),
        'pns' => Teacher::whereIn('status_kepegawaian', ['PNS', 'pns'])->count(),
        'pppk' => Teacher::whereIn('status_kepegawaian', ['PPPK', 'pppk'])->count(),
        'honorer' => Teacher::whereIn('status_kepegawaian', ['Honorer', 'honorer'])->count(),
      ],
      'documents' => Document::count(),
      'school_documents' => SchoolDocument::count(),
      'learning_activities' => [
        'total' => LearningActivity::count(),
        'pending' => LearningActivity::where('status', 'pending')->count(),
        'approved' => LearningActivity::where('status', 'approved')->count(),
        'rejected' => LearningActivity::where('status', 'rejected')->count(),
      ],
      'infrastructure' => [
        'total' => InfrastructureRequest::count(),
        'rkb' => InfrastructureRequest::where('type', 'RKB')->count(),
        'rehab' => InfrastructureRequest::where('type', 'REHAB')->count(),
        'other' => InfrastructureRequest::whereNotIn('type', ['RKB', 'REHAB'])->count(),
      ],
    ];

    // Age Statistics for Active Students
    $activeStudents = Student::whereIn('status_kelulusan', ['Aktif', 'aktif'])->get();
    $ageStats = [
      '< 7' => 0,
      '7-12' => 0,
      '13-15' => 0,
      '16-18' => 0,
      '> 18' => 0,
      'Kosong' => 0,
    ];

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

      $studentDetails[] = [
        'nama' => $student->nama,
        'kelas' => $student->classroom ? $student->classroom->nama_kelas : ($student->kelas ?? '-'),
        'gender' => $student->gender,
        'age' => $age,
        'birth_date' => $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-'
      ];
    }

    $stats['age_stats'] = $ageStats;
    $stats['student_details'] = $studentDetails;

    tenancy()->end();

    return view('backend.superadmin.reports.show', compact('tenant', 'stats'));
  }

  public function pdfExport($tenantId)
  {
    $tenant = Tenant::findOrFail($tenantId);
    tenancy()->initialize($tenant);

    // Reuse statistics logic (abstracted would be better, but doing it here for simplicity now)
    $stats = [
      'students' => [
        'total' => Student::count(),
        'active' => Student::whereIn('status_kelulusan', ['Aktif', 'aktif'])->count(),
        'graduated' => Student::whereIn('status_kelulusan', ['Lulus', 'lulus'])->count(),
        'others' => Student::whereNotIn('status_kelulusan', ['Aktif', 'aktif', 'Lulus', 'lulus'])->count(),
      ],
      'gender' => [
        'L' => Student::where('gender', 'L')->count(),
        'P' => Student::where('gender', 'P')->count(),
      ],
      'classrooms' => Classroom::count(),
      'classroom_stats' => Classroom::withCount([
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
      })->toArray(),
      'teachers' => [
        'total' => Teacher::count(),
        'pns' => Teacher::whereIn('status_kepegawaian', ['PNS', 'pns'])->count(),
        'pppk' => Teacher::whereIn('status_kepegawaian', ['PPPK', 'pppk'])->count(),
        'honorer' => Teacher::whereIn('status_kepegawaian', ['Honorer', 'honorer'])->count(),
      ],
      'documents' => Document::count(),
      'school_documents' => SchoolDocument::count(),
      'learning_activities' => [
        'total' => LearningActivity::count(),
        'pending' => LearningActivity::where('status', 'pending')->count(),
        'approved' => LearningActivity::where('status', 'approved')->count(),
        'rejected' => LearningActivity::where('status', 'rejected')->count(),
      ],
      'infrastructure' => [
        'total' => InfrastructureRequest::count(),
        'rkb' => InfrastructureRequest::where('type', 'RKB')->count(),
        'rehab' => InfrastructureRequest::where('type', 'REHAB')->count(),
        'other' => InfrastructureRequest::whereNotIn('type', ['RKB', 'REHAB'])->count(),
      ],
    ];

    $activeStudents = Student::whereIn('status_kelulusan', ['Aktif', 'aktif'])->get();
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
      $studentDetails[] = [
        'nama' => $student->nama,
        'kelas' => $student->classroom ? $student->classroom->nama_kelas : ($student->kelas ?? '-'),
        'gender' => $student->gender,
        'age' => $age,
        'birth_date' => $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-'
      ];
    }
    $stats['age_stats'] = $ageStats;
    $stats['student_details'] = $studentDetails;

    tenancy()->end();

    $pdf = Pdf::loadView('backend.superadmin.reports.pdf', compact('tenant', 'stats'))
      ->setPaper('a4', 'portrait');

    return $pdf->download('Laporan_' . $tenant->npsn . '_' . date('Ymd_His') . '.pdf');
  }
}
