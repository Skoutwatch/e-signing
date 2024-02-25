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
        Schema::create('schedule_session_requests', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->uuid('scheduled_session_id')->nullable()->constrained('scheduled_sessions')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('notary_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('status')->default('Awaiting');
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
        Schema::dropIfExists('schedule_session_requests');
    }
};
