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
        Schema::table('notary_schedules', function (Blueprint $table) {
            $table->dropColumn(['time']);
            $table->string('date')->nullable()->change();
            $table->string('day')->nullable()->after('date');
            $table->boolean('active')->default(false);
            $table->time('start_time')->nullable()->after('date');
            $table->time('end_time')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notary_schedules', function (Blueprint $table) {
            //
        });
    }
};
