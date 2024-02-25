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
        Schema::table('schedule_sessions', function (Blueprint $table) {
            $table->boolean('default_compliance_type')->default(true)->after('compliance_required');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_sessions', function (Blueprint $table) {
            $table->dropColumn('default_compliance_type');
        });
    }
};
