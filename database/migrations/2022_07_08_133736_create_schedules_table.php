<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('request_type')->nullable();
            $table->boolean('session')->default(false);
            $table->string('delivery_channel')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('delivery_email')->nullable();
            $table->uuid('schedule_id')->nullable();
            $table->string('schedule_type')->nullable();
            $table->uuid('service_plan_id')->nullable()->constrained('service_plans')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('parent_id')->nullable()->constrained('schedule_sessions')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('customer_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('notary_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->date('date')->nullable();
            $table->integer('set_reminder_in_minutes')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->longText('token')->nullable();
            $table->longText('meeting_link')->nullable();
            $table->longText('video_session_link')->nullable();
            $table->boolean('immediate')->default(false);
            $table->boolean('start_session')->default(false);
            $table->boolean('end_session')->default(false);
            $table->string('status')->default('New');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('schedules');
    }
};
