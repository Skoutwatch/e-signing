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
        Schema::create('schedule_session_recordings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('schedule_session_id')->nullable()->constrained('schedule_sessions')->cascadeOnUpdate()->nullOnDelete();
            $table->longText('video_recording_file')->nullable();
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
        Schema::dropIfExists('schedule_session_recordings');
    }
};
