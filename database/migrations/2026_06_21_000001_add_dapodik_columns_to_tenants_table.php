<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('dapodik_url')->nullable()->after('storage_limit');
            $table->string('dapodik_key')->nullable()->after('dapodik_url');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['dapodik_url', 'dapodik_key']);
        });
    }
};
