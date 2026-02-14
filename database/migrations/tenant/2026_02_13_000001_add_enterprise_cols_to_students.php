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
    Schema::table('students', function (Blueprint $table) {
      $columns = ['nik', 'birth_place', 'birth_date', 'address', 'parent_name', 'year_in', 'year_out'];
      $hasColumns = false;
      foreach ($columns as $column) {
        if (Schema::hasColumn('students', $column)) {
          $hasColumns = true; // Optimization: If any exist, we might skip or check individually. Let's check individually.
        }
      }

      Schema::table('students', function (Blueprint $table) {
        if (!Schema::hasColumn('students', 'nik')) {
          $table->string('nik', 20)->nullable()->index();
        }
        if (!Schema::hasColumn('students', 'birth_place')) {
          $table->string('birth_place')->nullable();
        }
        if (!Schema::hasColumn('students', 'birth_date')) {
          $table->date('birth_date')->nullable();
        }
        if (!Schema::hasColumn('students', 'address')) {
          $table->text('address')->nullable();
        }
        if (!Schema::hasColumn('students', 'parent_name')) {
          $table->string('parent_name')->nullable();
        }
        if (!Schema::hasColumn('students', 'year_in')) {
          $table->year('year_in')->nullable();
        }
        if (!Schema::hasColumn('students', 'year_out')) {
          $table->year('year_out')->nullable();
        }
      });

      // Fix: 'name' column might not exist if it was created as 'nama' in previous migration. 
      // Checking create_students_table content: 
      // $table->string('nama', 255); 
      // So we should refer to 'nama', not 'name'.
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('students', function (Blueprint $table) {
      $table->dropColumn(['nik', 'birth_place', 'birth_date', 'address', 'parent_name', 'year_in', 'year_out']);
    });
  }
};
