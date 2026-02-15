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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas'); // e.g., "X IPA 1", "7A"
            $table->string('tingkat')->nullable(); // e.g., "10", "7", "1"
            $table->string('jurusan')->nullable(); // e.g., "IPA", "IPS", "Teknik Mesin"
            $table->foreignId('wali_kelas_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('tahun_ajaran')->nullable(); // e.g., "2025/2026"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
