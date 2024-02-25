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
        Schema::create('pricings', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->string('name')->nullable();
            $table->string('entry_point')->nullable();
            $table->string('description')->nullable();
            $table->float('amount')->default(0);
            $table->boolean('initial_service_charge')->default(false);
            $table->uuid('plan_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('parent_id')->nullable()->constrained('pricings')->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('pricings');
    }
};
