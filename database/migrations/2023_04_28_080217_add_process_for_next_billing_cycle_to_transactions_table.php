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
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('process_for_next_billing_cycle')->default(true)->after('next_billing_cycle_deduction');
            $table->date('next_billing_cycle_date')->nullable()->after('process_for_next_billing_cycle');
            $table->boolean('next_billing_cycle_date_processed')->default(false)->after('next_billing_cycle_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('process_for_next_billing_cycle');
            $table->dropColumn('next_billing_cycle_date');
            $table->dropColumn('next_billing_cycle_date_processed');
        });
    }
};
