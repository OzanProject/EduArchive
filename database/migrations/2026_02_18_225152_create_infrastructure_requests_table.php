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
        Schema::create('infrastructure_requests', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->enum('type', ['RKB', 'REHAB']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->bigInteger('proposed_budget')->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_progress', 'completed'])->default('pending');
            $table->string('media_path')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructure_requests');
    }
};
