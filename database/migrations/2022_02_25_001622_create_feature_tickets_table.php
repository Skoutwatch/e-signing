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
        Schema::create('feature_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->decimal('charges')->nullable();
            $table->timestamp('expired_at');
            $table->uuid('feature_id')->nullable()->constrained('features')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();

            if (config('soulbscription.models.subscriber.uses_uuid')) {
                $table->uuidMorphs('subscriber');
            } else {
                $table->numericMorphs('subscriber');
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
        Schema::dropIfExists('feature_tickets');
    }
};
