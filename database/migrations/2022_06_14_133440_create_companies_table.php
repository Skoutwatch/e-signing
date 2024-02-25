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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->string('company_name')->nullable();
            $table->string('type')->nullable();
            $table->longText('logo')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('verify_me_email')->nullable();
            $table->string('verify_me_city')->nullable();
            $table->string('verify_me_state')->nullable();
            $table->string('verify_me_lga')->nullable();
            $table->string('classification')->nullable();
            $table->uuid('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('registration_company_number')->nullable();
            $table->string('registration_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('address')->nullable();
            $table->string('branch_address')->nullable();
            $table->string('head_office')->nullable();
            $table->string('lga')->nullable();
            $table->string('affiliates')->nullable();
            $table->string('share_capital')->nullable();
            $table->string('share_capital_in_words')->nullable();
            $table->string('status')->nullable();
            $table->uuid('country_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('state_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('city_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('companies');
    }
};
