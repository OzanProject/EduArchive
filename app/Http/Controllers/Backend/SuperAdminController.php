<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;

use Illuminate\Support\Facades\Cache;

class SuperAdminController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();

        // Cache the heavy statistics for 60 minutes
        $stats = Cache::remember('superadmin_dashboard_stats', 3600, function () use ($tenants) {
            $total_siswa = 0;
            $total_guru = 0;
            $total_dokumen = 0;
            $total_size_bytes = 0;

            foreach ($tenants as $tenant) {
                try {
                    $tenant->run(function () use (&$total_siswa, &$total_dokumen, &$total_guru) {
                        // We safely catch any missing table errors below
                        $total_siswa += \App\Models\Student::count();
                        $total_guru += \App\Models\Teacher::count();
                        $total_dokumen += \App\Models\Document::count();
                    });

                    // Gunakan data dari database alih-alih scanning folder rekursif
                    $total_size_bytes += $tenant->getUsedStorage();
                } catch (\Stancl\Tenancy\Exceptions\TenantDatabaseDoesNotExistException $e) {
                    continue;
                } catch (\Illuminate\Database\QueryException $e) {
                    // Abaikan error jika ada tabel yang belum di-migrate (misal: teachers)
                    continue;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to fetch stats for tenant {$tenant->id}: " . $e->getMessage());
                    continue;
                }
            }

            return [
                'total_siswa' => $total_siswa,
                'total_guru' => $total_guru,
                'total_dokumen' => $total_dokumen,
                'total_size_bytes' => $total_size_bytes,
            ];
        });

        // Fetch Recent Activity (Audit Logs)
        $recent_logs = \App\Models\AuditLog::with('user')->latest()->take(5)->get();

        // Fetch Recent Schools
        $recent_schools = Tenant::latest()->take(5)->get();

        // School Level Distribution
        $school_levels = $tenants->groupBy('jenjang')->map->count();

        // Convert bytes to MB or GB
        $bytes = $stats['total_size_bytes'];
        if ($bytes >= 1073741824) {
            $storage_usage = number_format($bytes / 1073741824, 2) . ' GB';
        } else {
            $storage_usage = number_format($bytes / 1048576, 2) . ' MB';
        }

        $data = [
            'total_sekolah' => $tenants->count(),
            'sekolah_aktif' => $tenants->where('status_aktif', true)->count(),
            'sekolah_pending' => $tenants->where('status_aktif', false)->count(),
            'total_user' => User::count(),
            'total_siswa' => $stats['total_siswa'],
            'total_guru' => $stats['total_guru'],
            'total_dokumen' => $stats['total_dokumen'],
            'storage_usage' => $storage_usage,
            'school_levels' => $school_levels,
        ];

        return view('backend.superadmin.dashboard', compact('data', 'recent_logs', 'recent_schools'));
    }
}
