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
        Schema::table('subscription_reserves', function (Blueprint $table) {
            $table->uuid('team_id')->nullable()->constrained('teams')->cascadeOnUpdate()->nullOnDelete()->after('user_id');
            $table->uuid('plan_id')->nullable()->constrained('plans')->cascadeOnUpdate()->nullOnDelete()->after('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_reserves', function (Blueprint $table) {
            //
        });
    }
};
