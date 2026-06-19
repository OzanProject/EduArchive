<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));

        $backups = [];
        foreach ($files as $k => $f) {
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $f),
                    'file_size' => $this->humanFilesize($disk->size($f)),
                    'created_at' => Carbon::createFromTimestamp($disk->lastModified($f))->timezone('Asia/Jakarta')->format('d-M-Y H:i:s'),
                    'timestamp' => $disk->lastModified($f)
                ];
            }
        }

        $backups = collect($backups)->sortByDesc(function ($b) { return $b['timestamp']; })->values()->all();

        return view('backend.superadmin.backups.index', compact('backups'));
    }

    public function create(Request $request)
    {
        try {
            $type = $request->input('type', 'db'); // db or full
            
            // Limit execution time just in case
            set_time_limit(300);

            if ($type === 'full') {
                Artisan::call('backup:run');
            } else {
                Artisan::call('backup:run', ['--only-db' => true]);
            }

            $output = Artisan::output();
            Log::info("Backup Output: " . $output);

            return back()->with('success', 'Backup berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error("Backup Error: " . $e->getMessage());
            return back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function download($file_name)
    {
        $file = config('backup.backup.name') . '/' . $file_name;
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);

        if ($disk->exists($file)) {
            return $disk->download($file);
        } else {
            return back()->with('error', 'File backup tidak ditemukan.');
        }
    }

    public function destroy($file_name)
    {
        $file = config('backup.backup.name') . '/' . $file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);

        if ($disk->exists($file)) {
            $disk->delete($file);
            return back()->with('success', 'File backup berhasil dihapus.');
        } else {
            return back()->with('error', 'File backup tidak ditemukan.');
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimetypes:text/plain,application/sql|max:102400', // max 100MB
        ]);

        try {
            $file = $request->file('sql_file');
            $sqlContent = file_get_contents($file->getRealPath());

            \Illuminate\Support\Facades\DB::unprepared($sqlContent);

            Log::info("Database successfully restored by Superadmin.");
            return back()->with('success', 'Database berhasil di-restore dari file SQL.');
        } catch (\Exception $e) {
            Log::error("Database Restore Error: " . $e->getMessage());
            return back()->with('error', 'Gagal melakukan restore database: ' . $e->getMessage());
        }
    }

    private function humanFilesize($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        return $size;
    }
}
