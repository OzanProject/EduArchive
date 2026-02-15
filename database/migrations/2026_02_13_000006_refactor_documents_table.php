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
    Schema::table('documents', function (Blueprint $table) {
      // 1. Rename 'jenis_dokumen' to 'document_type' if it exists and target doesn't
      if (Schema::hasColumn('documents', 'jenis_dokumen') && !Schema::hasColumn('documents', 'document_type')) {
        $table->renameColumn('jenis_dokumen', 'document_type');
      }

      // 2. Add new columns
      // Ensure we use 'file_path' as the anchor, as defined in create_documents_table
      $anchor = Schema::hasColumn('documents', 'file_path') ? 'file_path' : 'path';

      if (!Schema::hasColumn('documents', 'file_size')) {
        $table->integer('file_size')->nullable()->after($anchor);
      }
      if (!Schema::hasColumn('documents', 'mime_type')) {
        $table->string('mime_type')->nullable()->after('file_size');
      }
      if (!Schema::hasColumn('documents', 'uploaded_by')) {
        $table->unsignedBigInteger('uploaded_by')->nullable()->after('mime_type');
      }
      if (!Schema::hasColumn('documents', 'verified_at')) {
        $table->timestamp('verified_at')->nullable()->after('uploaded_by');
      }

      // 3. Drop legacy columns
      if (Schema::hasColumn('documents', 'is_verified')) {
        $table->dropColumn('is_verified');
      }
      // If 'path' exists and we want 'file_path' (and 'file_path' doesn't exist yet)
      if (Schema::hasColumn('documents', 'path') && !Schema::hasColumn('documents', 'file_path')) {
        $table->renameColumn('path', 'file_path');
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('documents', function (Blueprint $table) {
      if (Schema::hasColumn('documents', 'document_type')) {
        $table->renameColumn('document_type', 'jenis_dokumen');
      }
      $table->boolean('is_verified')->default(false);
      $table->dropColumn(['file_size', 'mime_type', 'uploaded_by', 'verified_at']);
    });
  }
};
