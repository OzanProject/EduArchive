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
      // School Master Data (Tenant)
      'teacher-list', 'teacher-create', 'teacher-edit', 'teacher-delete', 'teacher-import',
      'classroom-list', 'classroom-create', 'classroom-edit', 'classroom-delete',
      'student-list', 'student-create', 'student-edit', 'student-delete', 'student-import', 'student-bulk', 'student-print',
      'infrastructure-list', 'infrastructure-create', 'infrastructure-edit', 'infrastructure-delete',
      'learning-activity-list', 'learning-activity-create', 'learning-activity-edit', 'learning-activity-delete',
      
      // School Operations (Tenant)
      'integrity-pact-list', 'integrity-pact-create', 'integrity-pact-edit', 'integrity-pact-delete',
      'school-document-list', 'school-document-create', 'school-document-delete',
      'pip-list', 'pip-create', 'pip-delete', 'pip-import',
      
      // School Admin Specific
      'operator-list', 'operator-create', 'operator-edit', 'operator-delete',
      'tenant-setting-list', 'tenant-setting-edit',
      'tenant-report-list',
    ];

    foreach ($permissions as $permission) {
      Permission::firstOrCreate(['name' => $permission]);
    }

    // 1. Assign all permissions to Super Admin role
    $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
    $superadmin->syncPermissions(Permission::all());

    // 2. Assign Tenant Admin Permissions
    $adminSekolah = Role::firstOrCreate(['name' => 'admin_sekolah']);
    $adminSekolah->syncPermissions([
      'teacher-list', 'teacher-create', 'teacher-edit', 'teacher-delete', 'teacher-import',
      'classroom-list', 'classroom-create', 'classroom-edit', 'classroom-delete',
      'student-list', 'student-create', 'student-edit', 'student-delete', 'student-import', 'student-bulk', 'student-print',
      'infrastructure-list', 'infrastructure-create', 'infrastructure-edit', 'infrastructure-delete',
      'learning-activity-list', 'learning-activity-create', 'learning-activity-edit', 'learning-activity-delete',
      'integrity-pact-list', 'integrity-pact-create', 'integrity-pact-edit', 'integrity-pact-delete',
      'school-document-list', 'school-document-create', 'school-document-delete',
      'pip-list', 'pip-create', 'pip-delete', 'pip-import',
      'operator-list', 'operator-create', 'operator-edit', 'operator-delete',
      'tenant-setting-list', 'tenant-setting-edit',
      'tenant-report-list',
    ]);

    // 3. Assign Operator Permissions (Subset)
    $operator = Role::firstOrCreate(['name' => 'operator']);
    $operator->syncPermissions([
      'student-list', 'student-create', 'student-edit', 'student-import', 'student-bulk', 'student-print',
      'integrity-pact-list', 'integrity-pact-create', 'integrity-pact-edit',
      'school-document-list', 'school-document-create',
      'pip-list', 'pip-create', 'pip-import',
    ]);
  }
}
