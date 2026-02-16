<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $permissions = [
      // User Management
      'user-list',
      'user-create',
      'user-edit',
      'user-delete',
      'user-show',

      // Role Management
      'role-list',
      'role-create',
      'role-edit',
      'role-delete',

      // Tenant Management (School)
      'tenant-list',
      'tenant-create',
      'tenant-edit',
      'tenant-delete',
      'tenant-status', // Approve/Suspend

      // Monitor & Logs
      'monitoring-list',
      'audit-log-list',
      'broadcast-create',
      'broadcast-list',
      'broadcast-delete',

      // Settings
      'setting-list',
      'setting-edit',

      // Master Data (Super Admin)
      'document-type-list',
      'document-type-create',
      'document-type-edit',
      'document-type-delete',

      'page-list',
      'page-create',
      'page-edit',
      'page-delete',

      'school-level-list',
      'school-level-create',
      'school-level-edit',
      'school-level-delete',
    ];

    foreach ($permissions as $permission) {
      Permission::firstOrCreate(['name' => $permission]);
    }

    // Assign all permissions to Super Admin role
    $role = Role::firstOrCreate(['name' => 'superadmin']);
    $role->syncPermissions(Permission::all());
  }
}
