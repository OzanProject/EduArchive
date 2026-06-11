<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SchoolAdminController extends Controller
{
    public function index()
    {
        $broadcasts = \App\Models\Broadcast::where('is_active', true)->latest()->get();

        $tenantId = tenant('id');

        // Cache statistical data for 10 minutes (600 seconds)
        $data = Cache::remember("eduarchive-cache-tenant_dashboard_stats_{$tenantId}", 600, function () {
            return [
                'total_guru' => \App\Models\Teacher::count(),
                'total_siswa' => \App\Models\Student::where('status_kelulusan', 'Aktif')->count(),
                'total_kelas' => \App\Models\Classroom::count(),
            ];
        });

        // Storage Usage Logic
        $usage = \App\Models\StorageUsage::first();
        if (!$usage) {
            // Recalculate if empty
            $totalBytes = \App\Models\Document::sum('file_size') + \App\Models\SchoolDocument::sum('file_size'); // Assuming SchoolDocument also has file_size
            $usage = \App\Models\StorageUsage::create(['used_space' => $totalBytes, 'last_calculated' => now()]);
        }
        
        $usedBytes = $usage->used_space;
        $limitBytes = tenant('storage_limit');
        
        $data['storage_usage'] = $usedBytes;
        $data['storage_limit'] = $limitBytes;

        // Formatting logic moved from View to Controller (Clean Architecture)
        $data['storage_percentage'] = 0;
        $data['limit_formatted'] = 'Unlimited';
        $data['used_formatted'] = $this->formatBytes($usedBytes);

        if ($limitBytes !== null && $limitBytes > 0) {
            $data['storage_percentage'] = round(($usedBytes / $limitBytes) * 100, 1);
            $data['limit_formatted'] = $this->formatBytes($limitBytes);
        }

        // Fetch Recent Activity (Audit Logs) for this Tenant
        $recent_logs = \App\Models\AuditLog::where('tenant_id', $tenantId)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('backend.adminlembaga.dashboard', compact('broadcasts', 'data', 'recent_logs'));
    }

    private function formatBytes($bytes)
    {
        $bytes = max($bytes, 0);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $formatted = round($bytes / (1024 ** $pow), 2);
        
        return $formatted . ' ' . $units[$pow];
    }
}
