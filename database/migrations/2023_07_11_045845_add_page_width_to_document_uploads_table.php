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
        Schema::table('document_uploads', function (Blueprint $table) {
            // $table->float('page_width')->nullable();
            // $table->float('page_height')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            // $table->dropColumn('page_width');
            // $table->dropColumn('page_height');
        });
    }
};
