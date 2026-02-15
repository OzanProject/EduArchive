<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    $tables = [
      'students',
      'teachers',
      'classrooms',
      'documents',
      'school_documents',
      'notifications',
      'tenant_users',
      'storage_usages',
      'graduations',
      'imports',
      'app_settings',
      'users', // Add users table
    ];

    foreach ($tables as $table) {
      if (Schema::hasTable($table)) {
        Schema::table($table, function (Blueprint $table) {
          if (!Schema::hasColumn($table->getTable(), 'tenant_id')) {
            $table->string('tenant_id')->after('id')->nullable();
            $table->index('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
          }
        });
      }
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    $tables = [
      'students',
      'teachers',
      'classrooms',
      'documents',
      'school_documents',
      'notifications',
      'tenant_users',
      'storage_usages',
      'graduations',
      'imports',
      'app_settings',
      'users',
    ];

    foreach ($tables as $table) {
      if (Schema::hasTable($table)) {
        Schema::table($table, function (Blueprint $table) {
          $table->dropForeign(['tenant_id']);
          $table->dropColumn('tenant_id');
        });
      }
    }
  }
};
