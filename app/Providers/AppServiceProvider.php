<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share app settings via View Composer to support Tenancy/DB switching
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            try {
                // Use cache key based on domain/tenant to avoid collisions
                $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;
                $cacheKey = 'app_settings_' . ($tenantId ?? 'global');

                $app_settings = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () {
                    // Check if table exists to avoid migration errors
                    if (!\Illuminate\Support\Facades\Schema::hasTable('app_settings')) {
                        return [];
                    }
                    return \App\Models\AppSetting::all()->pluck('value', 'key')->toArray();
                });

                $view->with('app_settings', $app_settings);

                // Set Timezone dynamically
                if (isset($app_settings['app_timezone'])) {
                    config(['app.timezone' => $app_settings['app_timezone']]);
                    date_default_timezone_set($app_settings['app_timezone']);
                }

                // Set Mail Config dynamically
                if (isset($app_settings['mail_driver']) && $app_settings['mail_driver'] == 'smtp' && !empty($app_settings['mail_host'])) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $app_settings['mail_host'],
                        'mail.mailers.smtp.port' => $app_settings['mail_port'] ?? 587,
                        'mail.mailers.smtp.encryption' => $app_settings['mail_encryption'] ?? 'tls',
                        'mail.mailers.smtp.username' => $app_settings['mail_username'] ?? null,
                        'mail.mailers.smtp.password' => $app_settings['mail_password'] ?? null,
                        'mail.from.address' => $app_settings['mail_from_address'] ?? config('mail.from.address'),
                        'mail.from.name' => $app_settings['mail_from_name'] ?? config('mail.from.name'),
                    ]);
                }
            } catch (\Exception $e) {
                $view->with('app_settings', []);
            }

            // Always fetch Central/Dinas Logo for Favicon
            $dinas_logo = \Illuminate\Support\Facades\Cache::remember('dinas_app_logo', 3600, function () {
                try {
                    // Force connection to central database (usually 'mysql') to get Dinas settings
                    return \Illuminate\Support\Facades\DB::connection('mysql')
                        ->table('app_settings')
                        ->where('key', 'app_logo')
                        ->value('value');
                } catch (\Exception $e) {
                    return null;
                }
            });
            $view->with('dinas_logo', $dinas_logo);
        });

        // View Composer for Navbar (Dynamic Stats)
        \Illuminate\Support\Facades\View::composer('backend.layouts.partials.navbar', function ($view) {
            $student_count = \Illuminate\Support\Facades\Cache::remember('global_student_count', 600, function () { // Cache 10 mins
                $count = 0;
                try {
                    \App\Models\Tenant::all()->each(function ($tenant) use (&$count) {
                        try {
                            $tenant->run(function () use (&$count) {
                                if (\Illuminate\Support\Facades\Schema::hasTable('students')) {
                                    $count += \App\Models\Student::count();
                                }
                            });
                        } catch (\Throwable $e) {
                            // Ignore broken tenants
                        }
                    });
                } catch (\Exception $e) {
                    $count = 0;
                }
                return $count;
            });

            // Format: 12.5K or 1200
            $formatted_count = $student_count >= 1000
                ? number_format($student_count / 1000, 1) . 'K'
                : $student_count;

            $view->with('global_student_count', $formatted_count);

            // Tenant Specific Stats (if tenant context exists AND user is NOT superadmin)
            if (tenant() && auth()->check() && auth()->user()->role !== 'superadmin') {
                try {
                    $tenant_student_count = \Illuminate\Support\Facades\Cache::remember('tenant_student_count_' . tenant('id'), 300, function () {
                        return \App\Models\Student::count();
                    });
                    $view->with('tenant_student_count', $tenant_student_count);
                } catch (\Exception $e) {
                    // Fallback if table doesn't exist or connection fails
                    $view->with('tenant_student_count', 0);
                }
            }
        });

        // Register Observers
        \App\Models\Document::observe(\App\Observers\StorageObserver::class);
        \App\Models\SchoolDocument::observe(\App\Observers\StorageObserver::class);
    }
}
