<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        $total_siswa = 0;
        $total_dokumen = 0;

        // Iterate through tenants to count students and documents
        foreach ($tenants as $tenant) {
            try {
                $tenant->run(function () use (&$total_siswa, &$total_dokumen) {
                    // Check if table exists to avoid errors on empty/broken tenants
                    if (\Illuminate\Support\Facades\Schema::hasTable('students')) {
                        $total_siswa += \App\Models\Student::count();
                    }
                    if (\Illuminate\Support\Facades\Schema::hasTable('documents')) {
                        $total_dokumen += \App\Models\Document::count();
                    }
                });
            } catch (\Stancl\Tenancy\Exceptions\TenantDatabaseDoesNotExistException $e) {
                // Ignore missing databases, just skip this tenant
                continue;
            } catch (\Exception $e) {
                // Log other errors but don't crash dashboard
                \Illuminate\Support\Facades\Log::error("Failed to fetch stats for tenant {$tenant->id}: " . $e->getMessage());
                continue;
            }
        }

        // Fetch Recent Activity (Audit Logs)
        $recent_logs = \App\Models\AuditLog::with('user')->latest()->take(5)->get();

        // Calculate Storage Usage (Recursive)
        $storage_path = storage_path('app');
        $total_size = 0;

        try {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storage_path)) as $file) {
                $total_size += $file->getSize();
            }
        } catch (\Exception $e) {
            $total_size = 0; // Fallback if permission denied
        }

        $storage_usage = number_format($total_size / 1048576, 2) . ' MB'; // Convert bytes to MB

        $data = [
            'total_sekolah' => $tenants->count(),
            'sekolah_aktif' => $tenants->where('data.status_aktif', true)->count(), // Assuming 'status_aktif' is in data column or property
            'sekolah_suspended' => $tenants->count() - $tenants->where('data.status_aktif', true)->count(), // Simplified logic
            'total_user' => User::count(),
            'total_siswa' => $total_siswa,
            'total_dokumen' => $total_dokumen,
            'storage_usage' => $storage_usage,
        ];

        return view('backend.superadmin.dashboard', compact('data', 'recent_logs'));
    }
}
