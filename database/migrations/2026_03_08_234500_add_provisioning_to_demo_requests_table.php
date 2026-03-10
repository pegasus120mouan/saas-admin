<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_requests', function (Blueprint $table) {
            $table->string('tenant_id')->nullable()->after('assigned_to');
            $table->timestamp('provisioned_at')->nullable()->after('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::table('demo_requests', function (Blueprint $table) {
            $table->dropColumn(['tenant_id', 'provisioned_at']);
        });
    }
};
