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
            return response()->json(['success' => false, 'message' => 'Pengaturan Dapodik belum lengkap.'], 400);
        }

        $type = $request->data_type;
        $mode = $request->sync_mode;

        try {
            // Reset cache progress
            $cacheKey = "dapodik_sync_{$tenant->id}_{$type}";
            \Illuminate\Support\Facades\Cache::put($cacheKey, [
                'progress' => 0,
                'status' => 'queued',
                'message' => 'Menunggu antrean...',
                'timestamp' => time()
            ], now()->addHours(1));

            // Dispatch job
            \App\Jobs\SyncDapodikJob::dispatch($tenant, $type, $mode);

            return response()->json([
                'success' => true, 
                'message' => 'Proses sinkronisasi telah dimasukkan ke dalam antrean latar belakang.'
            ]);
        } catch (\Exception $e) {
            Log::error("Dapodik Sync Dispatch Error ($type): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function checkProgress(Request $request)
    {
        $type = $request->query('data_type');
        $tenant = tenant();
        
        $cacheKey = "dapodik_sync_{$tenant->id}_{$type}";
        $progress = \Illuminate\Support\Facades\Cache::get($cacheKey);

        if (!$progress) {
            return response()->json([
                'progress' => 0,
                'status' => 'idle',
                'message' => 'Tidak ada proses berjalan.'
            ]);
        }

        return response()->json($progress);
    }

    public function processQueue()
    {
        try {
            // Run queue worker with stop-when-empty so it doesn't run forever
            \Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
            
            return redirect()->back()->with('success', 'Antrean sinkronisasi Dapodik berhasil diproses!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses antrean: ' . $e->getMessage());
        }
    }
}
