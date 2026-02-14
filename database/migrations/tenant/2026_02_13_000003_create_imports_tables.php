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
    if (!Schema::hasTable('imports')) {
      Schema::create('imports', function (Blueprint $table) {
        $table->id();
        $table->string('file_name');
        $table->integer('total_rows')->default(0);
        $table->integer('success_rows')->default(0);
        $table->integer('failed_rows')->default(0);
        $table->unsignedBigInteger('imported_by')->nullable(); // Tenant User ID
        $table->timestamps();
      });
    }

    if (!Schema::hasTable('import_failures')) {
      Schema::create('import_failures', function (Blueprint $table) {
        $table->id();
        $table->foreignId('import_id')->constrained('imports')->cascadeOnDelete();
        $table->integer('row_number');
        $table->text('error_message');
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('import_failures');
    Schema::dropIfExists('imports');
  }
};
