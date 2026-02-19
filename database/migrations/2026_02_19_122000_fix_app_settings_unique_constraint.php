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
    Schema::table('app_settings', function (Blueprint $table) {
      // Check if the unique index exists before dropping
      // The default index name is app_settings_key_unique
      $table->dropUnique(['key']);

      // Add new composite unique index
      $table->unique(['key', 'tenant_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('app_settings', function (Blueprint $table) {
      $table->dropUnique(['key', 'tenant_id']);
      $table->unique('key');
    });
  }
};
