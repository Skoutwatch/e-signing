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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->uuid('payment_gateway_list_id')->nullable()->constrained('payment_gateways')->cascadeOnUpdate()->nullOnDelete();
            $table->string('type')->default('percentage');
            $table->float('percentage_gateway_charge')->nullable();
            $table->float('percentage_company_charge')->nullable();
            $table->float('amount_gateway_charge')->nullable();
            $table->float('amount_company_charge')->nullable();
            $table->float('total')->nullable();
            $table->uuid('country_id')->nullable()->constrained('countries')->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('apply_percentage_and_amount')->default(true);
            $table->boolean('active')->default(true);
            $table->boolean('keys')->default(true);
            $table->string('private_test_key')->nullable();
            $table->string('public_test_key')->nullable();
            $table->string('private_live_key')->nullable();
            $table->string('public_live_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
};
