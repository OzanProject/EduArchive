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
    Schema::create('audit_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
      $table->string('tenant_id')->nullable();
      $table->string('action'); // e.g., LOGIN, REQUEST_DOCUMENT
      $table->string('target_type')->nullable(); // e.g., App\Models\Tenant
      $table->string('target_id')->nullable();
      $table->string('ip_address')->nullable();
      $table->text('details')->nullable(); // Extra data in JSON or text
      $table->timestamps();

      $table->index(['user_id', 'created_at']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('audit_logs');
  }
};
