<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolAdminController extends Controller
{
    public function index()
    {
        $broadcasts = \App\Models\Broadcast::where('is_active', true)->latest()->get();

        // Ensure tenant context is active (handled by middleware usually)
        // For now, we assume simple counts from tenant tables
        // Note: 'Teacher' and 'Class' models might not exist yet based on file list.
        // Using placeholders or existing models.

        $data = [
            'total_guru' => \App\Models\Teacher::count(),
            'total_siswa' => \App\Models\Student::where('status_kelulusan', 'Aktif')->count(),
            'total_kelas' => \App\Models\Classroom::count(),
        ];

        // Storage Usage Logic
        $usage = \App\Models\StorageUsage::first();
        if (!$usage) {
            // Recalculate if empty
            $totalBytes = \App\Models\Document::sum('file_size') + \App\Models\SchoolDocument::sum('file_size'); // Assuming SchoolDocument also has file_size
            $usage = \App\Models\StorageUsage::create(['used_space' => $totalBytes, 'last_calculated' => now()]);
        }
        $data['storage_usage'] = $usage->used_space;
        $data['storage_limit'] = tenant('storage_limit') ?? 1073741824; // Default 1GB if not set on tenant

        return view('backend.adminlembaga.dashboard', compact('broadcasts', 'data'));
    }
}
