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
            $table->string('recipient_name')->nullable()->after('session_type');
            $table->string('recipient_email')->nullable()->after('recipient_name');
            $table->string('recipient_contact')->nullable()->after('recipient_email');
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
            //
        });
    }
};
