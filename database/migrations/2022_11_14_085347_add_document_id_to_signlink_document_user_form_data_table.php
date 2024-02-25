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
            $table->uuid('document_id')->nullable()->constrained('documents')->cascadeOnUpdate()->nullOnDelete()->after('phone');
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
