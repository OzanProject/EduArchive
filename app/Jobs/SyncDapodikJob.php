<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;

class SyncDapodikJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 menit maksimal untuk job ini
    public $tries = 1;

    protected $tenant;
    protected $type;
    protected $mode;

    public function __construct($tenant, $type, $mode)
    {
        $this->tenant = $tenant;
        $this->type = $type;
        $this->mode = $mode;
    }

    public function handle()
    {
        // Inisialisasi context tenant untuk Single-DB
        tenancy()->initialize($this->tenant);

        $this->updateProgress(0, 'processing', 'Memulai koneksi ke Dapodik...');

        try {
            if ($this->type === 'students') {
                $this->syncStudents();
            } elseif ($this->type === 'teachers') {
                $this->syncTeachers();
            } elseif ($this->type === 'classrooms') {
                $this->syncClassrooms();
            }
        } catch (\Exception $e) {
            Log::error("Dapodik Sync Job Error ({$this->type}): " . $e->getMessage());
            $this->updateProgress(100, 'error', 'Gagal: ' . $e->getMessage());
        } finally {
            tenancy()->end();
        }
    }

    private function updateProgress($percent, $status, $message)
    {
        $cacheKey = "dapodik_sync_{$this->tenant->id}_{$this->type}";
        Cache::put($cacheKey, [
            'progress' => $percent,
            'status' => $status,
            'message' => $message,
            'timestamp' => time()
        ], now()->addHours(1));
    }

    private function syncStudents()
    {
        $response = Http::withToken($this->tenant->dapodik_key)
            ->timeout(120)
            ->get($this->tenant->dapodik_url . '/WebService/getPesertaDidik', [
                'npsn' => $this->tenant->npsn
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gagal menghubungi Dapodik (HTTP {$response->status()})");
        }

        $data = $response->json();
        $rows = $data['rows'] ?? [];
        $total = count($rows);

        if ($total === 0) {
            $this->updateProgress(100, 'success', 'Selesai: Tidak ada data siswa di Dapodik.');
            return;
        }

        $this->updateProgress(5, 'processing', "Menemukan $total siswa. Memulai sinkronisasi...");

        // Ambil semua data siswa existing di memori agar hemat query (N+1 free)
        $existingStudents = Student::select('id', 'nisn', 'peserta_didik_id')->get()->keyBy(function($item) {
            return $item->nisn ?: $item->peserta_didik_id; // Prefer NISN, fallback to ID
        });

        $inserts = [];
        $countInsert = 0;
        $countUpdate = 0;
        $countSkip = 0;

        foreach ($rows as $index => $row) {
            $nisn = $row['nisn'] ?? null;
            $pesertaDidikId = $row['peserta_didik_id'] ?? uniqid();
            $identifier = $nisn ?: $pesertaDidikId;

            if ($existingStudents->has($identifier)) {
                if ($this->mode === 'skip') {
                    $countSkip++;
                } else {
                    // Overwrite mode
                    $student = $existingStudents->get($identifier);
                    Student::where('id', $student->id)->update([
                        'nama' => $row['nama'] ?? $student->nama,
                        'gender' => isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] == 'L' ? 'L' : 'P') : $student->gender,
                        'birth_place' => $row['tempat_lahir'] ?? null,
                        'birth_date' => $row['tanggal_lahir'] ?? null,
                    ]);
                    $countUpdate++;
                }
            } else {
                // Prepare array for insert (Batching)
                $inserts[] = [
                    'tenant_id' => $this->tenant->id,
                    'nisn' => $nisn,
                    'peserta_didik_id' => $pesertaDidikId, // If field exists, but let's just stick to what was there
                    'nama' => $row['nama'] ?? 'Fulan',
                    'gender' => isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] == 'L' ? 'L' : 'P') : 'L',
                    'birth_place' => $row['tempat_lahir'] ?? null,
                    'birth_date' => $row['tanggal_lahir'] ?? null,
                    'status_kelulusan' => 'Belum Lulus',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $countInsert++;
            }

            // Execute insert per 200 rows and update progress
            if (count($inserts) >= 200 || $index == $total - 1) {
                if (count($inserts) > 0) {
                    // Hapus kolom peserta_didik_id jika tidak ada di migration
                    $cleanInserts = array_map(function($item) {
                        unset($item['peserta_didik_id']);
                        return $item;
                    }, $inserts);
                    
                    Student::insert($cleanInserts);
                    $inserts = [];
                }
                
                $percent = round((($index + 1) / $total) * 90) + 5; // 5% - 95%
                $this->updateProgress($percent, 'processing', "Menyinkronkan siswa ke-" . ($index + 1) . " dari $total...");
            }
        }

        $this->updateProgress(100, 'success', "Selesai. Baru: $countInsert, Diupdate: $countUpdate, Dilewati: $countSkip.");
    }

    private function syncTeachers()
    {
        $response = Http::withToken($this->tenant->dapodik_key)
            ->timeout(120)
            ->get($this->tenant->dapodik_url . '/WebService/getGuru', [
                'npsn' => $this->tenant->npsn
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gagal menghubungi Dapodik (HTTP {$response->status()})");
        }

        $data = $response->json();
        $rows = $data['rows'] ?? [];
        $total = count($rows);

        if ($total === 0) {
            $this->updateProgress(100, 'success', 'Selesai: Tidak ada data guru di Dapodik.');
            return;
        }

        $this->updateProgress(5, 'processing', "Menemukan $total guru. Memulai sinkronisasi...");

        $existingTeachers = Teacher::select('id', 'nip')->get()->keyBy('nip');

        $inserts = [];
        $countInsert = 0;
        $countUpdate = 0;
        $countSkip = 0;

        foreach ($rows as $index => $row) {
            $nip = $row['nip'] ?? ($row['nik'] ?? ($row['ptk_id'] ?? uniqid()));
            $nama = $row['nama'] ?? 'Tanpa Nama';

            if ($existingTeachers->has($nip)) {
                if ($this->mode === 'skip') {
                    $countSkip++;
                } else {
                    $teacher = $existingTeachers->get($nip);
                    Teacher::where('id', $teacher->id)->update([
                        'nama_lengkap' => $nama,
                        'jenis_kelamin' => isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] == 'L' ? 'L' : 'P') : $teacher->jenis_kelamin,
                    ]);
                    $countUpdate++;
                }
            } else {
                $inserts[] = [
                    'tenant_id' => $this->tenant->id,
                    'nip' => $nip,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] == 'L' ? 'L' : 'P') : 'L',
                    'status_kepegawaian' => 'Lainnya',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $countInsert++;
            }

            if (count($inserts) >= 200 || $index == $total - 1) {
                if (count($inserts) > 0) {
                    Teacher::insert($inserts);
                    $inserts = [];
                }
                $percent = round((($index + 1) / $total) * 90) + 5;
                $this->updateProgress($percent, 'processing', "Menyinkronkan guru ke-" . ($index + 1) . " dari $total...");
            }
        }

        $this->updateProgress(100, 'success', "Selesai. Baru: $countInsert, Diupdate: $countUpdate, Dilewati: $countSkip.");
    }

    private function syncClassrooms()
    {
        $response = Http::withToken($this->tenant->dapodik_key)
            ->timeout(120)
            ->get($this->tenant->dapodik_url . '/WebService/getRombonganBelajar', [
                'npsn' => $this->tenant->npsn
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gagal menghubungi Dapodik (HTTP {$response->status()})");
        }

        $data = $response->json();
        $rows = $data['rows'] ?? [];
        $total = count($rows);

        if ($total === 0) {
            $this->updateProgress(100, 'success', 'Selesai: Tidak ada data rombongan belajar di Dapodik.');
            return;
        }

        $this->updateProgress(5, 'processing', "Menemukan $total rombel. Memulai sinkronisasi...");

        $existingClassrooms = Classroom::select('id', 'nama_kelas')->get()->keyBy('nama_kelas');

        $inserts = [];
        $countInsert = 0;
        $countUpdate = 0;
        $countSkip = 0;

        foreach ($rows as $index => $row) {
            $name = $row['nama'] ?? null;
            if (!$name) continue;

            if ($existingClassrooms->has($name)) {
                $countSkip++;
            } else {
                $inserts[] = [
                    'tenant_id' => $this->tenant->id,
                    'nama_kelas' => $name,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $countInsert++;
            }

            if (count($inserts) >= 50 || $index == $total - 1) {
                if (count($inserts) > 0) {
                    Classroom::insert($inserts);
                    $inserts = [];
                }
                $percent = round((($index + 1) / $total) * 90) + 5;
                $this->updateProgress($percent, 'processing', "Menyinkronkan rombel ke-" . ($index + 1) . " dari $total...");
            }
        }

        $this->updateProgress(100, 'success', "Selesai. Baru: $countInsert, Diupdate: $countUpdate, Dilewati: $countSkip.");
    }
}
