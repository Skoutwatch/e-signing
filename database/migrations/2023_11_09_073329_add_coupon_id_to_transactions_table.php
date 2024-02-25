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
        Schema::table('transactions', function (Blueprint $table) {
            $table->uuid('coupon_id')->after('payment_gateway_charge')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('referral_code')->after('coupon_id')->nullable();
            $table->string('discount_message')->after('referral_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
