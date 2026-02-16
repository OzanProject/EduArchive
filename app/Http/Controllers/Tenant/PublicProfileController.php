<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
  public function index()
  {
    // Fetch all settings
    $settings = AppSetting::all()->pluck('value', 'key');

    // Defaults from Tenant model if missing
    if (!isset($settings['school_name']) && tenant('nama_sekolah')) {
      $settings['school_name'] = tenant('nama_sekolah');
    }
    if (!isset($settings['school_logo']) && tenant('logo')) {
      $settings['school_logo'] = tenant_asset(tenant('logo'));
    }

    return view('tenant.profile', compact('settings'));
  }
  public function getDetail($type)
  {
    $html = '';
    $title = '';

    switch ($type) {
      case 'Siswa':
        $title = 'Data Siswa';
        $students = \App\Models\Student::with('classroom')->orderBy('nama')->get();
        $html = '<table class="table table-striped text-sm">
                        <thead><tr><th>No</th><th>Nama</th><th>L/P</th><th>Kelas</th></tr></thead>
                        <tbody>';
        foreach ($students as $index => $student) {
          $html .= '<tr>
                              <td>' . ($index + 1) . '</td>
                              <td>' . $student->nama . '</td>
                              <td>' . ($student->gender ?? '-') . '</td>
                              <td>' . ($student->classroom ? $student->classroom->nama_kelas : '-') . '</td>
                            </tr>';
        }
        $html .= '</tbody></table>';
        break;

      case 'Guru & Tendik':
        $title = 'Data Guru & Tenaga Kependidikan';
        $teachers = \App\Models\Teacher::orderBy('nama_lengkap')->get();
        $html = '<table class="table table-striped text-sm">
                        <thead><tr><th>No</th><th>Nama</th><th>NIP</th><th>Jabatan</th></tr></thead>
                        <tbody>';
        foreach ($teachers as $index => $teacher) {
          $html .= '<tr>
                              <td>' . ($index + 1) . '</td>
                              <td>' . $teacher->nama_lengkap . '</td>
                              <td>' . ($teacher->nip ?? '-') . '</td>
                              <td>-</td> 
                            </tr>';
        }
        $html .= '</tbody></table>';
        break;

      case 'Rombongan Belajar':
        $title = 'Data Rombongan Belajar';
        $classrooms = \App\Models\Classroom::withCount('students')->orderBy('nama_kelas')->get();
        $html = '<table class="table table-striped text-sm">
                        <thead><tr><th>No</th><th>Nama Kelas</th><th>Tingkat</th><th>Jumlah Siswa</th></tr></thead>
                        <tbody>';
        foreach ($classrooms as $index => $classroom) {
          $html .= '<tr>
                              <td>' . ($index + 1) . '</td>
                              <td>' . $classroom->nama_kelas . '</td>
                              <td>' . $classroom->tingkat . '</td>
                              <td>' . $classroom->students_count . '</td>
                            </tr>';
        }
        $html .= '</tbody></table>';
        break;

      case 'Sarana Prasarana':
        $title = 'Data Sarana Prasarana';
        $settings = AppSetting::all()->pluck('value', 'key');
        $html = '<ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          Ruang Kelas
                          <span class="badge badge-primary badge-pill">' . \App\Models\Classroom::count() . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          Laboratorium
                          <span class="badge badge-primary badge-pill">' . ($settings['school_lab_count'] ?? 0) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          Perpustakaan
                          <span class="badge badge-primary badge-pill">' . ($settings['school_library_count'] ?? 0) . '</span>
                        </li>
                       </ul>';
        break;

      case 'Nilai Akreditasi':
        $title = 'Nilai Akreditasi';
        $settings = AppSetting::all()->pluck('value', 'key');
        $html = '<div class="alert alert-info">
                          <h4 class="alert-heading">Akreditasi: ' . ($settings['school_accreditation'] ?? 'Belum Terakreditasi') . '</h4>
                          <p class="mb-0">Data detail nilai akreditasi belum tersedia secara rinci.</p>
                       </div>';
        break;

      case 'Sanitasi':
        $title = 'Data Sanitasi';
        $settings = AppSetting::all()->pluck('value', 'key');
        $html = '<div class="list-group">
                        <div class="list-group-item">
                            <h6 class="mb-1">Sumber Air Bersih</h6>
                            <p class="mb-1 text-muted">' . ($settings['school_water_source'] ?? 'Data Belum Diinput') . '</p>
                        </div>
                        <div class="list-group-item">
                            <h6 class="mb-1">Jamban/Toilet</h6>
                            <p class="mb-1 text-muted">' . ($settings['school_toilet_availability'] ?? 'Data Belum Diinput') . '</p>
                        </div>
                        <div class="list-group-item">
                            <h6 class="mb-1">Sanitasi Layak</h6>
                            <p class="mb-1 text-muted">' . ($settings['school_sanitation_percentage'] ?? '0') . '%</p>
                        </div>
                       </div>';
        break;

      default:
        $title = 'Detail ' . $type;
        $html = '<p class="text-center text-muted">Data detail belum tersedia.</p>';
        break;
    }

    return response()->json([
      'title' => $title,
      'html' => $html
    ]);
  }
}
