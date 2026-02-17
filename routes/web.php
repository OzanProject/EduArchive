<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\MonitoringController;
use App\Http\Controllers\Backend\TenantController;
use App\Http\Controllers\Backend\SuperAdminController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\BroadcastController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\DocumentAccessController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Models\AppSetting;

// Fallback Storage Route (Bypasses Symlink Issues)
Route::get('storage/{path}', function ($path) {
    if (str_contains($path, '../') || str_contains($path, '..\\')) {
        abort(403);
    }
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These details the "Central" application routes.
|
*/

// Root Route
// Root Route
// Root Route
Route::get('/', [\App\Http\Controllers\FrontendController::class, 'index'])->name('home');
Route::get('/fitur', [\App\Http\Controllers\FrontendController::class, 'features'])->name('features');
Route::get('/arsitektur', [\App\Http\Controllers\FrontendController::class, 'architecture'])->name('architecture');
Route::get('/keamanan', [\App\Http\Controllers\FrontendController::class, 'security'])->name('security');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Profile Management
    Route::prefix('admin')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Super Admin Routes
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');

        // Tenant Management
        Route::post('/tenants/bulk-action', [TenantController::class, 'bulkAction'])->name('tenants.bulk_action');
        Route::resource('tenants', TenantController::class);
        Route::patch('/tenants/{id}/status', [TenantController::class, 'updateStatus'])->name('tenants.status');

        // Tenant Asset Proxy for Super Admin
        Route::get('/tenants/{tenant}/assets/{path}', [TenantController::class, 'asset'])
            ->where('path', '.*')
            ->name('tenants.asset');

        // Monitoring Routes
        Route::prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/', [MonitoringController::class, 'index'])->name('index');
            Route::get('/{id}/print', [MonitoringController::class, 'printRecap'])->name('print_recap');
            Route::get('/audit-logs', [MonitoringController::class, 'auditLogs'])->name('audit_logs');
            Route::delete('/audit-logs/{id}', [MonitoringController::class, 'destroyAuditLog'])->name('audit_logs.destroy');
            Route::post('/document-access/request', [DocumentAccessController::class, 'requestAccess'])->name('document_access.request');

            // Specific Monitoring
            Route::get('/{id}', [MonitoringController::class, 'showSchool'])->name('school');
            Route::get('/{tenant_id}/student/{id}', [MonitoringController::class, 'showStudent'])->name('student');
            Route::get('/{tenant_id}/student/{id}/document/{document_id}/view', [MonitoringController::class, 'viewDocument'])->name('view_document');
            Route::post('/{tenant_id}/student/{id}/document/{document_id}', [MonitoringController::class, 'logAccess'])->name('access_document');
        });

        // Broadcast Logs
        Route::resource('broadcasts', BroadcastController::class)->only(['index', 'create', 'store', 'destroy']);

        // App Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::get('/general', [SettingController::class, 'general'])->name('general');
            Route::get('/landing', [SettingController::class, 'landing'])->name('landing');
            Route::get('/footer', [SettingController::class, 'footer'])->name('footer');
            Route::get('/smtp', [SettingController::class, 'smtp'])->name('smtp');
            Route::get('/whatsapp', [SettingController::class, 'whatsapp'])->name('whatsapp');
            Route::post('/update', [SettingController::class, 'update'])->name('update');
        });

        // Role Management (RBAC)
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);

        // Document Types
        // Document Types
        Route::resource('document-types', \App\Http\Controllers\Backend\DocumentTypeController::class);

        // Page Management
        Route::resource('pages', \App\Http\Controllers\Backend\PageController::class);

        // School Level Management
        Route::resource('school-levels', \App\Http\Controllers\Backend\SchoolLevelController::class);

        // Reports
        Route::resource('reports', \App\Http\Controllers\Backend\SuperAdminReportController::class)->only(['index', 'show']);
    });
});

Route::get('/p/{slug}', [\App\Http\Controllers\Frontend\PageController::class, 'show'])->name('page.show');

require __DIR__ . '/auth.php';

Route::get('/debug-logo', function () {
    $keys = ['app_logo', 'landing_logo', 'app_favicon', 'landing_hero_bg'];
    $results = [];

    foreach ($keys as $key) {
        $value = AppSetting::where('key', $key)->value('value');
        $url = $value ? Storage::url($value) : 'N/A';
        $path = $value ? storage_path('app/public/' . $value) : 'N/A';
        $exists = $value ? file_exists($path) : false;

        $results[$key] = [
            'db_value' => $value,
            'generated_url' => $url,
            'absolute_path' => $path,
            'file_exists_php' => $exists ? 'YES' : 'NO',
        ];
    }

    return $results;
});
