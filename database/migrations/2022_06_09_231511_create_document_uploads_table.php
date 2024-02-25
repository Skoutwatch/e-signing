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
        Schema::create('document_uploads', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->uuid('document_id')->nullable()->constrained('documents')->cascadeOnUpdate()->nullOnDelete();
            $table->longText('file')->nullable();
            $table->longText('file_url')->nullable();
            $table->string('type')->nullable();
            $table->string('base64_type')->nullable();
            $table->uuid('parent_id')->nullable()->constrained('document_uploads')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('status')->nullable();
            $table->string('public')->nullable(true);
            $table->boolean('display')->default(false);
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
        Schema::dropIfExists('document_uploads');
    }
};
