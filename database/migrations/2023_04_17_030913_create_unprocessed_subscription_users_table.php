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
        Schema::create('unprocessed_subscription_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_id')->nullable()->constrained('teams')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('transaction_id')->nullable()->constrained('transactions')->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('unprocessed_subscription_users');
    }
};
