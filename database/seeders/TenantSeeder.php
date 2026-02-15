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

    // Create Tenant if not exists
    $tenant = Tenant::firstOrCreate(
      ['id' => $tenantId],
      [
        'npsn' => '40404040',
        'nama_sekolah' => $tenantName,
        'jenjang' => 'SMP',
        'alamat' => 'Jl. Pendidikan No. 123',
        'status_aktif' => 1,
      ]
    );

    // Ensure Domain Record Exists (Optional for Path-Based, but good for consistency)
    // $tenant->domains()->firstOrCreate(['domain' => $tenantId]);

    $this->command->info("Tenant '{$tenantName}' is ready.");

    // Create Admin User for this Tenant
    // Create Admin User for this Tenant
    // Initialize tenancy context so that User::create automatically sets tenant_id
    \Stancl\Tenancy\Facades\Tenancy::initialize($tenant);

    $user = User::updateOrCreate(
      ['email' => $adminEmail],
      [
        'name' => 'Admin ' . $tenantName,
        'password' => Hash::make('12345678'),
        'role' => 'admin_sekolah',
        'email_verified_at' => now(),
      ]
    );

    // Assign Role if using Spatie (optional check)
    // if (method_exists($user, 'assignRole')) {
    //   $user->assignRole('admin_sekolah');
    // }

    $this->command->info("Admin user '{$adminEmail}' created for tenant.");

    \Stancl\Tenancy\Facades\Tenancy::end();
  }
}
