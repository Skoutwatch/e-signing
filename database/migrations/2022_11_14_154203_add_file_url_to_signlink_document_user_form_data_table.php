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
        Schema::table('signlink_document_user_form_data', function (Blueprint $table) {
            $table->longText('file')->nullable()->after('document_id');
            $table->longText('file_url')->nullable()->after('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signlink_document_user_form_data', function (Blueprint $table) {
            //
        });
    }
};
