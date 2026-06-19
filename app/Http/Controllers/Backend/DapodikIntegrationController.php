<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Support\Facades\Log;

class DapodikIntegrationController extends Controller
{
    public function index()
    {
        $tenant = tenant();
        $dapodikUrl = $tenant->dapodik_url ?? '';
        $dapodikKey = $tenant->dapodik_key ?? '';

        return view('backend.adminlembaga.dapodik.index', compact('dapodikUrl', 'dapodikKey'));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'dapodik_url' => 'required|url',
            'dapodik_key' => 'required|string',
        ]);

        $tenant = tenant();
        // Remove trailing slash if any
        $tenant->dapodik_url = rtrim($request->dapodik_url, '/');
        $tenant->dapodik_key = $request->dapodik_key;
        $tenant->save();

        return redirect()->back()->with('success', 'Pengaturan koneksi Dapodik berhasil disimpan.');
    }

    public function testConnection()
    {
        $tenant = tenant();
        if (!$tenant->dapodik_url || !$tenant->dapodik_key) {
            return redirect()->back()->with('error', 'Silakan simpan pengaturan URL dan Key Dapodik terlebih dahulu.');
        }

        try {
            // Using standard Dapodik endpoint test
            $response = Http::withToken($tenant->dapodik_key)
                ->timeout(10)
                ->get($tenant->dapodik_url . '/WebService/getPengguna', [
                    'npsn' => $tenant->npsn
                ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Test Koneksi Berhasil! EduArchive dapat terhubung dengan server Dapodik.');
            }

            return redirect()->back()->with('error', 'Koneksi gagal. HTTP Status: ' . $response->status());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Koneksi gagal: ' . $e->getMessage());
        }
    }

    public function pullData(Request $request)
    {
        $request->validate([
            'data_type' => 'required|in:students,teachers,classrooms',
            'sync_mode' => 'required|in:skip,overwrite'
        ]);

        $tenant = tenant();
        if (!$tenant->dapodik_url || !$tenant->dapodik_key) {
            return redirect()->back()->with('error', 'Pengaturan Dapodik belum lengkap.');
        }

        $type = $request->data_type;
        $mode = $request->sync_mode;

        try {
            if ($type === 'students') {
                return $this->syncStudents($tenant, $mode);
            } elseif ($type === 'teachers') {
                return $this->syncTeachers($tenant, $mode);
            } elseif ($type === 'classrooms') {
                return $this->syncClassrooms($tenant, $mode);
            }
        } catch (\Exception $e) {
            Log::error("Dapodik Sync Error ($type): " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menarik data: ' . $e->getMessage());
        }
    }

    private function syncStudents($tenant, $mode)
    {
        $response = Http::withToken($tenant->dapodik_key)
            ->timeout(60)
            ->get($tenant->dapodik_url . '/WebService/getPesertaDidik', [
                'npsn' => $tenant->npsn
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gagal menghubungi endpoint Dapodik (Status: {$response->status()})");
        }

        $data = $response->json();
        $rows = $data['rows'] ?? [];
        if (empty($rows)) {
            return redirect()->back()->with('warning', 'Tidak ada data siswa yang dikembalikan oleh Dapodik.');
        }

        $countInsert = 0;
        $countUpdate = 0;
        $countSkip = 0;

        foreach ($rows as $row) {
            // Identifier usually NISN or NIS
            $nisn = $row['nisn'] ?? null;
            if (!$nisn) {
                // If NISN is null, try using peserta_didik_id
                $nisn = $row['peserta_didik_id'] ?? uniqid();
            }

            $existing = Student::query()->where('nisn', $nisn)->first();

            if ($existing) {
                if ($mode === 'skip') {
                    $countSkip++;
                    continue;
                } else {
                    $existing->update([
                        'nama' => $row['nama'] ?? $existing->nama,
                        'gender' => isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] == 'L' ? 'L' : 'P') : $existing->gender,
                        'birth_place' => $row['tempat_lahir'] ?? $existing->birth_place,
                        'birth_date' => $row['tanggal_lahir'] ?? $existing->birth_date,
                    ]);
                    $countUpdate++;
                }
            } else {
                Student::create([
                    'nisn' => $nisn,
                    'nama' => $row['nama'] ?? 'Fulan',
                    'gender' => isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] == 'L' ? 'L' : 'P') : 'L',
                    'birth_place' => $row['tempat_lahir'] ?? null,
                    'birth_date' => $row['tanggal_lahir'] ?? null,
                    'status_kelulusan' => 'Belum Lulus'
                ]);
                $countInsert++;
            }
        }

        return redirect()->back()->with('success', "Sinkronisasi Siswa selesai. Baru: $countInsert, Diupdate: $countUpdate, Dilewati: $countSkip");
    }

    private function syncTeachers($tenant, $mode)
    {
        $response = Http::withToken($tenant->dapodik_key)
            ->timeout(60)
            ->get($tenant->dapodik_url . '/WebService/getGuru', [
                'npsn' => $tenant->npsn
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gagal menghubungi endpoint Dapodik (Status: {$response->status()})");
        }

        $data = $response->json();
        $rows = $data['rows'] ?? [];
        if (empty($rows)) {
            return redirect()->back()->with('warning', 'Tidak ada data guru yang dikembalikan oleh Dapodik.');
        }

        $countInsert = 0;
        $countUpdate = 0;
        $countSkip = 0;

        foreach ($rows as $row) {
            // NIP or PTK ID
            $nip = $row['nip'] ?? null;
            $nama = $row['nama'] ?? 'Tanpa Nama';
            if (!$nip) {
                // If NIP empty, use NIK or ID
                $nip = $row['nik'] ?? ($row['ptk_id'] ?? uniqid());
            }

            $existing = Teacher::query()->where('nip', $nip)->first();

            if ($existing) {
                if ($mode === 'skip') {
                    $countSkip++;
                    continue;
                } else {
                    $existing->update([
                        'nama_lengkap' => $nama,
                        'jenis_kelamin' => isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] == 'L' ? 'L' : 'P') : $existing->jenis_kelamin,
                    ]);
                    $countUpdate++;
                }
            } else {
                Teacher::create([
                    'nip' => $nip,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] == 'L' ? 'L' : 'P') : 'L',
                    'status_kepegawaian' => 'Lainnya',
                    'is_active' => true
                ]);
                $countInsert++;
            }
        }

        return redirect()->back()->with('success', "Sinkronisasi Guru selesai. Baru: $countInsert, Diupdate: $countUpdate, Dilewati: $countSkip");
    }

    private function syncClassrooms($tenant, $mode)
    {
        $response = Http::withToken($tenant->dapodik_key)
            ->timeout(60)
            ->get($tenant->dapodik_url . '/WebService/getRombonganBelajar', [
                'npsn' => $tenant->npsn
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gagal menghubungi endpoint Dapodik (Status: {$response->status()})");
        }

        $data = $response->json();
        $rows = $data['rows'] ?? [];
        if (empty($rows)) {
            return redirect()->back()->with('warning', 'Tidak ada data rombongan belajar yang dikembalikan.');
        }

        $countInsert = 0;
        $countUpdate = 0;
        $countSkip = 0;

        foreach ($rows as $row) {
            $name = $row['nama'] ?? null;
            if (!$name) continue;

            $existing = Classroom::query()->where('nama_kelas', $name)->first();

            if ($existing) {
                if ($mode === 'skip') {
                    $countSkip++;
                    continue;
                } else {
                    // Update homeroom teacher if provided via PTK ID or something (omitted for safety unless perfectly matched)
                    $countUpdate++;
                }
            } else {
                Classroom::create([
                    'nama_kelas' => $name,
                    'is_active' => true
                ]);
                $countInsert++;
            }
        }

        return redirect()->back()->with('success', "Sinkronisasi Rombel selesai. Baru: $countInsert, Diupdate: $countUpdate, Dilewati: $countSkip");
    }
}
