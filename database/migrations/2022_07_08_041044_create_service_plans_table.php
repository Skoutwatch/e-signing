<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_plans', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());

            $table->string('name');

            $table->float('unit_count')->default(1);

            $table->boolean('apply_additional_price')->default(false);
            $table->float('additional_unit_price')->default(0);
            $table->integer('additional_price_measured_unit')->default(0);

            $table->time('start_business_time')->default('5:00:00');
            $table->time('end_business_time')->default('17:00:00');

            $table->integer('session_minutes_time')->default(15);

            $table->float('notary_fee_cost')->default(0);
            $table->float('regulatory_fee_cost')->default(0);
            $table->float('other_partners_cost')->default(0);
            $table->float('tonote_service_cost')->default(0);
            $table->float('agora_cost')->default(0);
            $table->float('aws_cost')->default(0);
            $table->float('email_cost')->default(0);
            $table->float('customer_support_cost')->default(0);
            $table->float('verifyme_cost')->default(0);
            $table->float('payment_processing_cost')->default(0);
            $table->float('total')->default(0);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_plans');
    }
};
