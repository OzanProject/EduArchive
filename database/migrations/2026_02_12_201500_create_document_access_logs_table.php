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
    Schema::create('document_access_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Super Admin
      $table->string('tenant_id'); // ID Sekolah (string from tenant id)
      $table->string('student_nisn'); // NISN Siswa
      $table->string('document_name'); // Nama Dokumen (Ijazah, dll)
      $table->string('ip_address')->nullable();
      $table->string('user_agent')->nullable();
      $table->timestamp('accessed_at')->useCurrent();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('document_access_logs');
  }
};
