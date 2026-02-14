<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Check if super admin already exists
    if (!User::where('email', 'ardiansyahdzan@gmail.com')->exists()) {
      User::create([
        'name' => 'Super Administrator',
        'email' => 'ardiansyahdzan@gmail.com',
        'role' => 'superadmin',
        'password' => Hash::make('ardiansyah'), // Default password
        'email_verified_at' => now(),
      ]);
    }
  }
}
