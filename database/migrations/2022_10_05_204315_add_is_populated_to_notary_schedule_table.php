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
            $table->dropColumn(['start_time', 'end_time']);
            $table->time('time')->nullable()->after('date');
            $table->boolean('is_populated')->default(false)->after('time');
            $table->uuid('parent_id')->nullable()->constrained('notary_schedules')->cascadeOnUpdate()->nullOnDelete()->after('is_populated');
            $table->date('date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notary_schedule', function (Blueprint $table) {
            //
        });
    }
};
