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

        // Cache the public stats for 1 hour (3600 seconds)
        $stats = \Illuminate\Support\Facades\Cache::remember('tenant_public_stats_' . tenant('id'), 3600, function () {
            $data = [
                'students' => 0,
                'teachers' => 0,
                'classrooms' => 0,
            ];

            try {
                $data['students'] = \App\Models\Student::count();
                $data['teachers'] = \App\Models\Teacher::count();
                $data['classrooms'] = \App\Models\Classroom::count();
            } catch (\Exception $e) {
                // Return 0 if tables don't exist, preventing Error 500
                \Illuminate\Support\Facades\Log::error("Failed to fetch public stats for tenant " . tenant('id') . ": " . $e->getMessage());
            }

            return $data;
        });

        return view('tenant.welcome', compact('stats'));
    })->name('tenant.home'); // added name for easier redirection

    Route::get('/profil', [\App\Http\Controllers\Tenant\PublicProfileController::class, 'index'])->name('tenant.profile');
    Route::get('/profil/detail/{type}', [\App\Http\Controllers\Tenant\PublicProfileController::class, 'getDetail'])->name('tenant.profile.detail');

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
        Route::post('students/bulk-promote', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkPromote'])->name('students.bulkPromote');
        Route::post('students/bulk-promote-rombel', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkPromoteRombel'])->name('students.bulkPromoteRombel');
        Route::post('students/bulk-graduate', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkGraduate'])->name('students.bulkGraduate');
        Route::post('students/bulk-cancel-graduate', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkCancelGraduate'])->name('students.bulkCancelGraduate');
        Route::post('students/bulk-graduate-rombel', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkGraduateRombel'])->name('students.bulkGraduateRombel');
        Route::post('students/{student}/cancel-graduate', [\App\Http\Controllers\Backend\TenantStudentController::class, 'cancelGraduate'])->name('students.cancelGraduate');
        Route::get('students/{student}/print', [\App\Http\Controllers\Backend\TenantStudentController::class, 'print'])->name('students.print');
        Route::resource('students', \App\Http\Controllers\Backend\TenantStudentController::class);

        // Rekap Dokumen
        Route::get('rekap-dokumen', [\App\Http\Controllers\Backend\TenantStudentController::class, 'rekapDokumen'])->name('rekap_dokumen');
        Route::get('rekap-dokumen/print', [\App\Http\Controllers\Backend\TenantStudentController::class, 'rekapDokumenPrint'])->name('rekap_dokumen.print');

        Route::resource('infrastructure', \App\Http\Controllers\Backend\InfrastructureController::class);

        Route::resource('learning-activities', \App\Http\Controllers\Backend\LearningActivityController::class);
        Route::resource('integrity-pacts', \App\Http\Controllers\Backend\IntegrityPactController::class);
        Route::resource('documents', \App\Http\Controllers\Backend\DocumentController::class);
        Route::resource('school-documents', \App\Http\Controllers\Backend\SchoolDocumentController::class)->except(['show', 'edit', 'update']);
        Route::resource('users', \App\Http\Controllers\Backend\TenantUserController::class); // Manage Operators
        Route::get('reports', [\App\Http\Controllers\Backend\ReportController::class, 'index'])->name('reports.index');

        // PIP Routes
        Route::get('pip/template', [\App\Http\Controllers\Backend\TenantPipController::class, 'template'])->name('pip.template');
        Route::post('pip/import', [\App\Http\Controllers\Backend\TenantPipController::class, 'import'])->name('pip.import');
        Route::resource('pip', \App\Http\Controllers\Backend\TenantPipController::class)->except(['show', 'edit', 'update']);

        // Settings
        Route::get('settings/profile', [\App\Http\Controllers\Backend\SchoolSettingController::class, 'editProfile'])->name('settings.profile');
        Route::get('settings', [\App\Http\Controllers\Backend\SchoolSettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Backend\SchoolSettingController::class, 'update'])->name('settings.update');

        // Web Service API Tokens
        Route::get('api-tokens', [\App\Http\Controllers\Backend\ApiTokenController::class, 'index'])->name('api_tokens.index');
        Route::post('api-tokens', [\App\Http\Controllers\Backend\ApiTokenController::class, 'store'])->name('api_tokens.store');
        Route::delete('api-tokens/{tokenId}', [\App\Http\Controllers\Backend\ApiTokenController::class, 'destroy'])->name('api_tokens.destroy');

        // Dapodik Integration (Pull Data)
        Route::get('dapodik', [\App\Http\Controllers\Backend\DapodikIntegrationController::class, 'index'])->name('dapodik.index');
        Route::post('dapodik/save', [\App\Http\Controllers\Backend\DapodikIntegrationController::class, 'saveSettings'])->name('dapodik.save');
        Route::post('dapodik/test', [\App\Http\Controllers\Backend\DapodikIntegrationController::class, 'testConnection'])->name('dapodik.test');
        Route::post('dapodik/pull', [\App\Http\Controllers\Backend\DapodikIntegrationController::class, 'pullData'])->name('dapodik.pull');
        Route::post('dapodik/process-queue', [\App\Http\Controllers\Backend\DapodikIntegrationController::class, 'processQueue'])->name('dapodik.processQueue');
        Route::get('dapodik/progress', [\App\Http\Controllers\Backend\DapodikIntegrationController::class, 'checkProgress'])->name('dapodik.progress');


        // Guide
        Route::get('guide', [\App\Http\Controllers\Backend\GuideController::class, 'index'])->name('guide');
    });

    // Operator Routes
    Route::middleware(['auth', 'verified'])->prefix('operator')->name('operator.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Backend\OperatorController::class, 'index'])->name('dashboard');
        Route::get('students/template', [\App\Http\Controllers\Backend\TenantStudentController::class, 'downloadTemplate'])->name('students.template');
        Route::post('students/import', [\App\Http\Controllers\Backend\TenantStudentController::class, 'import'])->name('students.import');
        Route::post('students/bulk-delete', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkDelete'])->name('students.bulkDestroy');
        Route::get('students/bulk-print', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkPrint'])->name('students.bulkPrint');
        Route::post('students/bulk-promote', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkPromote'])->name('students.bulkPromote');
        Route::post('students/bulk-promote-rombel', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkPromoteRombel'])->name('students.bulkPromoteRombel');
        Route::post('students/bulk-graduate', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkGraduate'])->name('students.bulkGraduate');
        Route::post('students/bulk-cancel-graduate', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkCancelGraduate'])->name('students.bulkCancelGraduate');
        Route::post('students/bulk-graduate-rombel', [\App\Http\Controllers\Backend\TenantStudentController::class, 'bulkGraduateRombel'])->name('students.bulkGraduateRombel');
        Route::post('students/{student}/cancel-graduate', [\App\Http\Controllers\Backend\TenantStudentController::class, 'cancelGraduate'])->name('students.cancelGraduate');
        Route::get('students/{student}/print', [\App\Http\Controllers\Backend\TenantStudentController::class, 'print'])->name('students.print');
        Route::resource('students', \App\Http\Controllers\Backend\TenantStudentController::class);

        Route::resource('documents', \App\Http\Controllers\Backend\DocumentController::class);
        Route::resource('integrity-pacts', \App\Http\Controllers\Backend\IntegrityPactController::class);
        Route::resource('school-documents', \App\Http\Controllers\Backend\SchoolDocumentController::class)->except(['show', 'edit', 'update']);

        // PIP Routes
        Route::get('pip/template', [\App\Http\Controllers\Backend\TenantPipController::class, 'template'])->name('pip.template');
        Route::post('pip/import', [\App\Http\Controllers\Backend\TenantPipController::class, 'import'])->name('pip.import');
        Route::resource('pip', \App\Http\Controllers\Backend\TenantPipController::class)->except(['show', 'edit', 'update']);

        // Guide
        Route::get('guide', [\App\Http\Controllers\Backend\GuideController::class, 'index'])->name('guide');
    });
});

