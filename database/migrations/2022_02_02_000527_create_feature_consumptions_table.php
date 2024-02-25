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
        Schema::create('feature_consumptions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->unsignedDecimal('consumption')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->uuid('feature_id')->nullable()->constrained('features')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();

            if (config('soulbscription.models.subscriber.uses_uuid')) {
                $table->nullableUuidMorphs('subscriber');
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
        Schema::dropIfExists('feature_consumptions');
    }
};
