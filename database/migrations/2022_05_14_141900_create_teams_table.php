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
        Schema::create('teams', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->string('name')->nullable();
            $table->uuid('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('notify_owner_when_document_complete')->default(true);
            $table->boolean('notify_owner_when_a_signer_refuse_to_sign')->default(true);
            $table->boolean('notify_owner_when_each_signer_views_a_document')->default(true);
            $table->boolean('notify_owner_always_cc_admin')->default(false);
            $table->boolean('notify_signer_when_to_sign_a_document')->default(true);
            $table->boolean('notify_signer_when_document_complete')->default(true);
            $table->boolean('notify_signer_when_signer_declines_to_sign_document')->default(true);
            $table->boolean('notify_signer_when_owner_withdraws_document')->default(true);
            $table->boolean('notify_signer_always_cc_admin')->default(true);
            $table->boolean('notify_signer_when_document_updated')->default(true);
            $table->boolean('notify_display_transaction_to_team')->default(true);
            $table->boolean('notify_display_transaction_as_mine')->default(true);
            $table->boolean('send_sms')->default(false);
            $table->boolean('send_email')->default(true);
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
        Schema::dropIfExists('company_users');
    }
};
