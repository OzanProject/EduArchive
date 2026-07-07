<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;

class FrontendController extends Controller
{
  public function index(Request $request)
  {
    $settings = AppSetting::all()->pluck('value', 'key')->toArray();

    $dinas_logo = \Illuminate\Support\Facades\Cache::get('dinas_app_logo');
    $defaultLogo = asset('adminlte/dist/img/AdminLTELogo.png');

    if (isset($settings['landing_logo']) && $settings['landing_logo']) {
      $logo = asset($settings['landing_logo']);
    } elseif ($dinas_logo) {
      $logo = $dinas_logo;
    } elseif (isset($settings['app_logo']) && $settings['app_logo']) {
      $logo = asset($settings['app_logo']);
    } else {
      $logo = $defaultLogo;
    }

    $favicon = null;
    if (isset($settings['app_favicon']) && $settings['app_favicon']) {
      $favicon = asset($settings['app_favicon']);
    } elseif ($dinas_logo) {
      $favicon = $dinas_logo;
    } elseif (isset($settings['app_logo']) && $settings['app_logo']) {
      $favicon = asset($settings['app_logo']);
    } else {
      $favicon = $defaultLogo;
    }

    $districts = \DB::table('app_settings')
        ->where('key', 'school_district')
        ->whereNotNull('value')
        ->where('value', '!=', '')
        ->select('value')
        ->distinct()
        ->orderBy('value')
        ->pluck('value')
        ->toArray();

    $selectedDistrict = $request->get('district');
    $selectedNpsn = $request->get('npsn');
    $tenantQuery = \App\Models\Tenant::where('status_aktif', 1);

    if (!empty($selectedDistrict)) {
        $filteredTenantIds = \DB::table('app_settings')
            ->where('key', 'school_district')
            ->where('value', $selectedDistrict)
            ->pluck('tenant_id')
            ->toArray();
        $tenantQuery->whereIn('id', $filteredTenantIds);
    }
    
    if (!empty($selectedNpsn)) {
        $tenantQuery->where('npsn', 'LIKE', '%' . $selectedNpsn . '%');
    }

    $tenants = $tenantQuery->orderBy('nama_sekolah')->get();

    $schoolProgress   = $this->getSchoolProgressData($tenants);
    $profileProgress  = $this->getProfileProgressData($tenants);
    $documentProgress = $this->getDocumentProgressData($tenants);

    $partnerTenants = \App\Models\Tenant::where('status_aktif', 1)
        ->whereNotNull('logo')
        ->where('logo', '!=', '')
        ->inRandomOrder()
        ->limit(6)
        ->get();

    return view('frontend.index', compact('settings', 'logo', 'favicon', 'schoolProgress', 'profileProgress', 'documentProgress', 'districts', 'selectedDistrict', 'selectedNpsn', 'partnerTenants'));
  }

  public function progress(Request $request)
  {
    $data = $this->getCommonData();

    // Ambil daftar kecamatan yang ada datanya
    $districts = \DB::table('app_settings')
        ->where('key', 'school_district')
        ->whereNotNull('value')
        ->where('value', '!=', '')
        ->select('value')
        ->distinct()
        ->orderBy('value')
        ->pluck('value')
        ->toArray();

    $selectedDistrict = $request->get('district');
    $selectedNpsn = $request->get('npsn');

    // Query tenant dengan filter (jika ada)
    $tenantQuery = \App\Models\Tenant::where('status_aktif', 1);

    if (!empty($selectedDistrict)) {
        // Cari ID tenant yang kecamatannya sesuai
        $filteredTenantIds = \DB::table('app_settings')
            ->where('key', 'school_district')
            ->where('value', $selectedDistrict)
            ->pluck('tenant_id')
            ->toArray();
            
        $tenantQuery->whereIn('id', $filteredTenantIds);
    }

    if (!empty($selectedNpsn)) {
        $tenantQuery->where('npsn', 'LIKE', '%' . $selectedNpsn . '%');
    }

    $tenants = $tenantQuery->orderBy('nama_sekolah')->get();

    // School Progress: Three types of progress data for tabs
    $schoolProgress   = $this->getSchoolProgressData($tenants);      // Tab 1: NISN completeness
    $profileProgress  = $this->getProfileProgressData($tenants);     // Tab 2: Profile completeness
    $documentProgress = $this->getDocumentProgressData($tenants);    // Tab 3: Document upload

    $data = array_merge($data, compact('schoolProgress', 'profileProgress', 'documentProgress', 'districts', 'selectedDistrict', 'selectedNpsn'));
    return view('frontend.progress', $data);
  }

  private function getSchoolProgressData($tenants): array
  {
    $progress = [];

    foreach ($tenants as $tenant) {
      // Single-database multi-tenancy: query by tenant_id directly
      $total = \DB::table('students')
        ->where('tenant_id', $tenant->id)
        ->count();

      $completed = \DB::table('students')
        ->where('tenant_id', $tenant->id)
        ->whereNotNull('nisn')
        ->where('nisn', '!=', '')
        ->count();

      $sisa = max(0, $total - $completed);
      $pct  = $total > 0 ? round(($completed / $total) * 100) : 0;

      $progress[] = [
        'nama_sekolah' => $tenant->nama_sekolah ?? $tenant->id,
        'npsn'         => $tenant->npsn ?? '-',
        'jenjang'      => $tenant->jenjang ?? '-',
        'pct'          => $pct,
        'total'        => $total,
        'sent'         => $completed,
        'sisa'         => $sisa,
      ];
    }

    // Urutkan dari yang progresnya tertinggi
    usort($progress, fn($a, $b) => $b['pct'] <=> $a['pct']);

    return $progress;
  }

  private function getProfileProgressData($tenants): array
  {
    // Key profile fields to check completeness
    $profileFields = [
      'school_name', 'school_accreditation', 'school_curriculum',
      'school_headmaster_name', 'school_address', 'school_phone',
      'school_vision', 'school_mission', 'school_logo',
    ];
    $totalFields = count($profileFields);

    $progress = [];
    foreach ($tenants as $tenant) {
      $tenantSettings = \DB::table('app_settings')
        ->where('tenant_id', $tenant->id)
        ->whereIn('key', $profileFields)
        ->whereNotNull('value')
        ->where('value', '!=', '')
        ->pluck('value', 'key');

      $completed = 0;
      foreach ($profileFields as $field) {
        if (!empty($tenantSettings[$field])) $completed++;
      }
      // Also count tenant record data
      if (!empty($tenant->nama_sekolah)) $completed = max($completed, 1);

      $pct = $totalFields > 0 ? round(($completed / $totalFields) * 100) : 0;

      $progress[] = [
        'nama_sekolah' => $tenant->nama_sekolah ?? $tenant->id,
        'npsn'         => $tenant->npsn ?? '-',
        'jenjang'      => $tenant->jenjang ?? '-',
        'pct'          => $pct,
        'total'        => $totalFields,
        'sent'         => $completed,
        'sisa'         => $totalFields - $completed,
      ];
    }

    usort($progress, fn($a, $b) => $b['pct'] <=> $a['pct']);
    return $progress;
  }

  private function getDocumentProgressData($tenants): array
  {
    $progress = [];

    foreach ($tenants as $tenant) {
      // Total semua siswa lembaga ini
      $totalStudents = \DB::table('students')
        ->where('tenant_id', $tenant->id)
        ->count();

      // Dokumen per status validasi
      $docsQuery = \DB::table('documents')->where('tenant_id', $tenant->id);
      $totalDocs    = (clone $docsQuery)->count();
      $approved     = (clone $docsQuery)->where('validation_status', 'approved')->count();
      $pending      = (clone $docsQuery)->where('validation_status', 'pending')
                        ->orWhere(function($q) use ($tenant) {
                          $q->whereNull('validation_status')->where('tenant_id', $tenant->id);
                        })->count();
      $rejected     = (clone $docsQuery)->where('validation_status', 'rejected')->count();

      // Siswa yang sudah upload minimal 1 dokumen
      $withDocs = \DB::table('students')
        ->where('students.tenant_id', $tenant->id)
        ->whereExists(function ($query) use ($tenant) {
          $query->select(\DB::raw(1))
            ->from('documents')
            ->whereColumn('documents.student_id', 'students.id')
            ->where('documents.tenant_id', $tenant->id);
        })
        ->count();

      // Siswa yang semua dokumennya sudah disetujui (approved)
      $fullyApproved = \DB::table('students')
        ->where('students.tenant_id', $tenant->id)
        ->whereExists(function ($query) use ($tenant) {
          $query->select(\DB::raw(1))
            ->from('documents')
            ->whereColumn('documents.student_id', 'students.id')
            ->where('documents.tenant_id', $tenant->id)
            ->where('validation_status', 'approved');
        })
        ->whereNotExists(function ($query) use ($tenant) {
          $query->select(\DB::raw(1))
            ->from('documents')
            ->whereColumn('documents.student_id', 'students.id')
            ->where('documents.tenant_id', $tenant->id)
            ->where(function($q) {
              $q->where('validation_status', 'pending')
                ->orWhereNull('validation_status');
            });
        })
        ->count();

      $sisa = max(0, $totalStudents - $withDocs);
      $pct  = $totalStudents > 0 ? round(($withDocs / $totalStudents) * 100) : 0;

      $progress[] = [
        'nama_sekolah'   => $tenant->nama_sekolah ?? $tenant->id,
        'npsn'           => $tenant->npsn ?? '-',
        'jenjang'        => $tenant->jenjang ?? '-',
        'pct'            => $pct,
        'total'          => $totalStudents,
        'sent'           => $withDocs,
        'sisa'           => $sisa,
        'total_docs'     => $totalDocs,
        'approved'       => $approved,
        'pending'        => $pending,
        'rejected'       => $rejected,
        'fully_approved' => $fullyApproved,
      ];
    }

    usort($progress, fn($a, $b) => $b['pct'] <=> $a['pct']);
    return $progress;
  }

  public function features()
  {
    $data = $this->getCommonData();
    return view('frontend.features', $data);
  }

  public function architecture()
  {
    $data = $this->getCommonData();
    return view('frontend.architecture', $data);
  }

  public function security()
  {
    $data = $this->getCommonData();
    return view('frontend.security', $data);
  }

  private function getCommonData()
  {
    $settings = AppSetting::all()->pluck('value', 'key')->toArray();
    $dinas_logo = \Illuminate\Support\Facades\Cache::get('dinas_app_logo');
    $defaultLogo = asset('adminlte/dist/img/AdminLTELogo.png');

    if (isset($settings['landing_logo']) && $settings['landing_logo']) {
      $logo = asset($settings['landing_logo']);
    } elseif ($dinas_logo) {
      $logo = $dinas_logo;
    } elseif (isset($settings['app_logo']) && $settings['app_logo']) {
      $logo = asset($settings['app_logo']);
    } else {
      $logo = $defaultLogo;
    }

    $favicon = null;
    if (isset($settings['app_favicon']) && $settings['app_favicon']) {
      $favicon = asset($settings['app_favicon']);
    } elseif ($dinas_logo) {
      $favicon = $dinas_logo;
    } elseif (isset($settings['app_logo']) && $settings['app_logo']) {
      $favicon = asset($settings['app_logo']);
    } else {
      $favicon = $defaultLogo;
    }

    return compact('settings', 'logo', 'favicon');
  }
}
