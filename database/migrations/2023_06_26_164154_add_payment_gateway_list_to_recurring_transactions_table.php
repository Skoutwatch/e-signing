<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->uuid('payment_gateway_list_id')->nullable()->constrained('payment_gateway_lists')->cascadeOnUpdate()->nullOnDelete()->after('payment_gateway');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            //
        });
    }
};
