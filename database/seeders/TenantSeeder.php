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
    $dbName = $tenant->database()->getName();
    $this->command->info("Checking Tenant Database: {$dbName}...");

    try {
      $tenant->database()->makeCredentials();
      $manager = $tenant->database()->manager();

      // Check if database exists, if not create it
      if (!$manager->databaseExists($dbName)) {
        $this->command->info("Database {$dbName} does not exist. Attempting to create...");
        try {
          $manager->createDatabase($tenant);
          $this->command->info("Database successfully created.");
        } catch (\Exception $e) {
          $this->command->error("FAILED to auto-create database.");
          $this->command->error("Reason: " . $e->getMessage());
          $this->command->alert("ACTION REQUIRED FOR SHARED HOSTING:");
          $this->command->warn("1. Go to your cPanel -> MySQL Databases.");
          $this->command->warn("2. Create a database named: {$dbName}");
          // Extract username from env or config
          $defaultConnection = config('database.default');
          $dbUser = config("database.connections.{$defaultConnection}.username");
          if (!$dbUser) {
            $dbUser = env('DB_USERNAME', 'your_db_user');
          }
          $this->command->warn("3. Add user '{$dbUser}' to database '{$dbName}' with ALL PRIVILEGES.");
          $this->command->warn("4. Run this seed command again.");
          return; // Stop here
        }
      } else {
        $this->command->info("Database {$dbName} already exists.");
      }

      // Run Migrations
      $this->command->info("Migrating tenant database...");
      Artisan::call('tenants:migrate', [
        '--tenants' => [$tenant->id],
        '--force' => true,
      ]);

    } catch (\Exception $e) {
      $this->command->error("Error setting up tenant database: " . $e->getMessage());
      $this->command->warn("Ensure database '{$dbName}' exists and the user has permissions.");
      return;
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
