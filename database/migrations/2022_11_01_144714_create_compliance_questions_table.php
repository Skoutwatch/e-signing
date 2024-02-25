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
        Schema::create('compliance_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('compliance_type')->nullable();
            $table->uuid('compliance_id')->nullable();
            $table->longText('question')->nullable();
            $table->boolean('default')->default(false);
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
        Schema::dropIfExists('compliance_questions');
    }
};
