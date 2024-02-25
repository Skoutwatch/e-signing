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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid()->toString());
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('phone')->nullable();
            $table->string('verify_me_phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('user_access_code')->nullable();
            $table->integer('user_access_code_counter')->default(0);
            $table->string('registration_mode')->nullable();
            $table->longText('image')->nullable();
            $table->string('role')->nullable();
            $table->date('dob')->nullable();
            $table->boolean('is_business')->default(false);
            $table->string('identity_type')->nullable();
            $table->string('bvn')->nullable();
            $table->string('nin')->nullable();
            $table->string('drivers_license_no')->nullable();
            $table->string('drivers_license_issue_date')->nullable();
            $table->string('drivers_license_expiry_date')->nullable();
            $table->string('drivers_license_issue_state')->nullable();
            $table->boolean('system_verification')->default(false);
            $table->boolean('national_verification')->default(false);
            $table->boolean('is_notary')->default(false);
            $table->string('notary_commission_number')->default(false);
            $table->boolean('api_access')->default(false);
            $table->boolean('free_trial')->default(false);
            $table->integer('trial')->default(0);
            $table->string('api_mode')->default('Test');
            $table->string('api_token')->nullable();
            $table->string('live_secret_key')->nullable();
            $table->string('test_secret_key')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('address')->nullable();
            $table->string('username')->nullable();
            $table->uuid('referral_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('country_id')->nullable()->constrained('countries')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('state_id')->nullable()->constrained('state')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('city_id')->nullable()->constrained('cities')->cascadeOnUpdate()->nullOnDelete();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
