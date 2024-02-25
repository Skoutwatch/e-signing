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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('code', 60)->unique()->index();
            $table->unsignedBigInteger('visits')->default(0);
            $table->tinyInteger('discount_type')->default(\App\Enums\AffiliateDiscountType::Percentage);
            $table->tinyInteger('partner_type')->default(\App\Enums\AffiliatePartnerType::AffiliatePartner);
            $table->float('affiliate_discount')->default(0);
            $table->float('customer_discount')->default(0);
            $table->string('company', 120)->nullable();
            $table->string('job_title', 80)->nullable();
            $table->string('more_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
