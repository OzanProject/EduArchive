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

    // Create Tenant if not exists
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

    // Initialize Tenant Database
    try {
      $tenant->database()->makeCredentials();
      $manager = $tenant->database()->manager();

      // Check if database exists, if not create it
      if (!$manager->databaseExists($tenant->database()->getName())) {
        $this->command->info("Creating database for tenant: {$tenant->id}");
        $manager->createDatabase($tenant);
      } else {
        $this->command->info("Database for tenant {$tenant->id} already exists.");
      }

      // Run Migrations
      $this->command->info("Migrating tenant database...");
      Artisan::call('tenants:migrate', [
        '--tenants' => [$tenant->id],
        '--force' => true,
      ]);

    } catch (\Exception $e) {
      $this->command->error("Error setting up tenant database: " . $e->getMessage());
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
      $this->command->info("Admin user created for tenant.");
    });
  }
}
