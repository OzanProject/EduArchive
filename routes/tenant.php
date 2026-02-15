<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
*/

// Path-based Tenancy: http://localhost:8000/tenant_id/
Route::group([
    'prefix' => '/{tenant}',
    'middleware' => [
        InitializeTenancyByPath::class,
        'web',
    ],
], function () {

    Route::get('tenancy/assets/{path?}', [\App\Http\Controllers\Tenant\AssetController::class, 'asset'])
        ->where('path', '(.*)')
        ->name('stancl.tenancy.asset');

    Route::get('/', function () {
        $user = auth()->user();
        if ($user) {
            if ($user->role === 'admin_sekolah') {
                return redirect()->route('adminlembaga.dashboard', ['tenant' => tenant('id')]);
            } elseif ($user->role === 'operator') {
                return redirect()->route('operator.dashboard', ['tenant' => tenant('id')]);
            }
        }
        return view('tenant.welcome');
    })->name('tenant.home'); // added name for easier redirection

    // Tenant Dashboard (Redirect to specific dashboard based on role)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin_sekolah') {
            return redirect()->route('adminlembaga.dashboard', ['tenant' => tenant('id')]);
        } elseif ($user->role === 'operator') {
            return redirect()->route('operator.dashboard', ['tenant' => tenant('id')]);
        }
        return abort(403, 'Unauthorized access.');
    })->middleware(['auth', 'verified'])->name('tenant.dashboard');

    // Tenant Profile Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'edit'])->name('tenant.profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'update'])->name('tenant.profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\Tenant\ProfileController::class, 'updatePassword'])->name('tenant.profile.password.update');
        Route::delete('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'destroy'])->name('tenant.profile.destroy');
    });

    // Login Routes for Tenant
    // Namespace them to avoid conflict with central auth routes
    Route::name('tenant.')->group(function () {
        require __DIR__ . '/auth.php';
    });

    // Admin Lembaga Routes
    Route::middleware(['auth', 'verified'])->prefix('adminlembaga')->name('adminlembaga.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Backend\SchoolAdminController::class, 'index'])->name('dashboard');

        // Resources
        Route::get('teachers/template', [\App\Http\Controllers\Backend\TeacherController::class, 'downloadTemplate'])->name('teachers.template');
        Route::post('teachers/import', [\App\Http\Controllers\Backend\TeacherController::class, 'import'])->name('teachers.import');
        Route::resource('teachers', \App\Http\Controllers\Backend\TeacherController::class);

        Route::resource('classrooms', \App\Http\Controllers\Backend\ClassroomController::class);

        Route::get('students/template', [\App\Http\Controllers\Backend\TenantStudentController::class, 'downloadTemplate'])->name('students.template');
        Route::post('students/import', [\App\Http\Controllers\Backend\TenantStudentController::class, 'import'])->name('students.import');
        Route::post('students/bulk-delete', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkDelete'])->name('students.bulkDestroy');
        Route::get('students/bulk-print', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkPrint'])->name('students.bulkPrint');
        Route::get('students/{student}/print', [\App\Http\Controllers\Backend\TenantStudentController::class, 'print'])->name('students.print');
        Route::resource('students', \App\Http\Controllers\Backend\TenantStudentController::class);
        Route::resource('documents', \App\Http\Controllers\Backend\DocumentController::class)->except(['edit', 'update']);
        Route::resource('school-documents', \App\Http\Controllers\Backend\SchoolDocumentController::class)->except(['show', 'edit', 'update']);
        Route::get('reports', [\App\Http\Controllers\Backend\ReportController::class, 'index'])->name('reports.index');

        // Settings
        Route::get('settings', [\App\Http\Controllers\Backend\SchoolSettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Backend\SchoolSettingController::class, 'update'])->name('settings.update');
    });

    // Operator Routes
    Route::middleware(['auth', 'verified'])->prefix('operator')->name('operator.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Backend\OperatorController::class, 'index'])->name('dashboard');
    });
});

