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
      $table->string('subscription_plan')->nullable()->after('status_aktif');
      $table->bigInteger('storage_limit')->default(1073741824)->after('subscription_plan'); // Default 1GB
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('tenants', function (Blueprint $table) {
      $table->dropColumn(['subscription_plan', 'storage_limit']);
    });
  }
};
