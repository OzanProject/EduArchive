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
            $table->enum('validation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('verified_at');
            $table->text('validation_notes')->nullable()->after('validation_status');
            $table->unsignedBigInteger('validated_by')->nullable()->after('validation_notes');
            $table->timestamp('validated_at')->nullable()->after('validated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['validation_status', 'validation_notes', 'validated_by', 'validated_at']);
        });
    }
};
