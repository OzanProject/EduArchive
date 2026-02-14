<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class TenantSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $tenantId = 'smpn4percobaan';
    $tenantName = 'SMPN 4 PERCOBAAN';
    $adminEmail = 'admin@gmail.com';
    $adminPassword = 'password';

    $tenant = Tenant::find($tenantId);

    if (!$tenant) {
      $tenant = Tenant::create([
        'id' => $tenantId,
        'npsn' => '40404040',
        'nama_sekolah' => $tenantName,
        'jenjang' => 'SMP',
        'alamat' => 'Jl. Pendidikan No. 123',
        'status_aktif' => 1,
      ]);
    }

    // Ensure Domain Record Exists
    $tenant->domains()->firstOrCreate(['domain' => $tenantId]);

    // self-healing db and migration
    try {
      $tenant->database()->makeCredentials();
      if (!$tenant->database()->manager()->databaseExists($tenant->database()->getName())) {
        $tenant->database()->manager()->createDatabase($tenant);
      }

      Artisan::call('tenants:migrate', [
        '--tenants' => [$tenant->id],
        '--force' => true,
      ]);
    } catch (\Exception $e) {
      // Ignore
    }

    // Create Admin User
    $tenant->run(function () use ($tenantName, $adminEmail) {
      User::updateOrCreate(
        ['email' => $adminEmail],
        [
          'name' => 'Admin ' . $tenantName,
          'password' => Hash::make('12345678'),
          'role' => 'admin_sekolah',
          'email_verified_at' => now(),
        ]
      );
    });
  }
}
