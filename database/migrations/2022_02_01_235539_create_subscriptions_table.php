<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->uuid('plan_id')->nullable()->constrained('plans')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('grace_days_ended_at')->nullable();
            $table->date('started_at');
            $table->timestamp('suppressed_at')->nullable();
            $table->boolean('was_switched')->default(false);
            $table->softDeletes();
            $table->timestamps();

            if (config('soulbscription.models.subscriber.uses_uuid')) {
                $table->uuidMorphs('subscriber');
            } else {
                $table->uuidMorphs('subscriber');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
