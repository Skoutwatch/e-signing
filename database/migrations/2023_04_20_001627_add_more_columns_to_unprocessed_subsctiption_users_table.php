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
        Schema::table('unprocessed_subscription_users', function (Blueprint $table) {
            $table->string('permission')->default('Admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unprocessed_subscription_users', function (Blueprint $table) {
            $table->dropColumn('permission');
        });
    }
};
