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
    Schema::table('tenants', function (Blueprint $table) {
      // Change enum to string to support dynamic values
      $table->string('jenjang', 50)->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('tenants', function (Blueprint $table) {
      // Revert back to enum if needed (might lose data if values are not in enum)
      // Ideally we shouldn't revert this if we want to keep dynamic data.
      // But for completeness:
      // $table->enum('jenjang', ['SD', 'SMP', 'SMA', 'SMK'])->change();
    });
  }
};
