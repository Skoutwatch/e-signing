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
        Schema::create('compliance_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('compliance_question_id')->nullable()->constrained('compliance_questions')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('notary_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('response_type')->nullable();
            $table->uuid('response_id')->nullable();
            $table->string('document_type')->nullable();
            $table->uuid('document_id')->nullable();
            $table->string('schedule_type')->nullable();
            $table->uuid('schedule_id')->nullable();
            $table->longText('answer')->nullable();
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
        Schema::dropIfExists('compliance_responses');
    }
};
