<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_mutations', function (Blueprint $table) {
            $table->unsignedBigInteger('to_tenant_id')->nullable()->change();
        });

        DB::statement("ALTER TABLE student_mutations MODIFY COLUMN status ENUM('moved', 'returned', 'dropped_out') DEFAULT 'moved'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE student_mutations MODIFY COLUMN status ENUM('moved', 'returned') DEFAULT 'moved'");
        
        Schema::table('student_mutations', function (Blueprint $table) {
            $table->unsignedBigInteger('to_tenant_id')->nullable(false)->change();
        });
    }
};
