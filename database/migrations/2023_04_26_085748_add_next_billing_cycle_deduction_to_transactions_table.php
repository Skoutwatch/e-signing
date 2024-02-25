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
            $table->float('next_billing_cycle_deduction')->default(0)->after('recurring_end_date');
            $table->string('upgrade_type')->nullable()->after('next_billing_cycle_deduction');
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
            $table->dropColumn('next_billing_cycle_deduction');
            $table->dropColumn('upgrade_type');
        });
    }
};
