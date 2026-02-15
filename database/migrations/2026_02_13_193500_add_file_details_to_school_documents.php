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
    Schema::table('school_documents', function (Blueprint $table) {
      if (!Schema::hasColumn('school_documents', 'file_size')) {
        $table->integer('file_size')->nullable()->after('file_path');
      }
      if (!Schema::hasColumn('school_documents', 'mime_type')) {
        $table->string('mime_type')->nullable()->after('file_size');
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('school_documents', function (Blueprint $table) {
      $table->dropColumn(['file_size', 'mime_type']);
    });
  }
};
