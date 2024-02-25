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
        Schema::create('document_resources', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->uuid('document_upload_id')->nullable()->constrained('document_uploads')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('document_id')->nullable()->constrained('documents')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('who_added_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('append_print_id')->nullable()->constrained('append_prints')->cascadeOnUpdate()->nullOnDelete();
            $table->string('tool_name')->nullable();
            $table->string('tool_class')->nullable();
            $table->string('tool_width')->nullable();
            $table->string('tool_height')->nullable();
            $table->string('tool_pos_top')->nullable();
            $table->string('tool_pos_left')->nullable();
            $table->uuid('resource_id')->nullable();
            $table->string('resource_type')->nullable();
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
        Schema::dropIfExists('document_resources');
    }
};
