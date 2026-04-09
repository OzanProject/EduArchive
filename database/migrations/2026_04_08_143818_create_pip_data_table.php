<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pip_data', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id'); // Single-DB Tenancy needs this
            $table->string('nisn')->nullable();
            $table->string('nama_siswa');
            $table->year('tahun_usulan')->nullable();
            $table->string('tahap')->nullable();
            $table->decimal('nominal', 15, 2)->nullable();
            // Status pengusulan
            $table->enum('status', ['usulan_sekolah', 'diproses_dinas', 'disetujui', 'ditolak'])->default('usulan_sekolah');
            // Komunikasi Dua Arah
            $table->text('pesan_lembaga')->nullable();
            $table->text('pesan_dinas')->nullable();
            
            $table->timestamps();

            // Setup proper indexes and foreign key
            $table->index('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pip_data');
    }
};
