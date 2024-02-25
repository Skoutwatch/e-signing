<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Enums\AffiliatePartnerType;
use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function registration_is_successful(): void
    {
        $this->seed();

        //$this->withExceptionHandling();

        $response = $this->post('api/v1/affiliates/register', [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'company' => fake()->company,
            'email' => fake()->safeEmail,
            'job_title' => fake()->jobTitle,
            'phone' => fake()->e164PhoneNumber,
            'partner_type' => AffiliatePartnerType::getRandomValue(),
            'more_info' => fake()->sentence,
        ]);

        $affiliate = Affiliate::with('user')
            ->first();

        $response->assertStatus(201);
        $this->assertCount(1, Affiliate::all());

        $this->assertInstanceOf(Affiliate::class, $affiliate);
        $this->assertInstanceOf(User::class, $affiliate->user);

        $this->assertIsString($affiliate->code);

        $response->assertJson([
            'message' => 'Your affiliate account has been created',
        ]);
    }

    /**
     * @test
     */
    public function register_with_missing_fields(): void
    {
        $this->seed();

        $response = $this->post('api/v1/affiliates/register', []);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'phone', 'company', 'partner_type']);
    }

    /**
     * @test
     */
    public function affiliate_is_already_registered(): void
    {
        $this->seed();

        $email = fake()->safeEmail;

        $response = $this->post('api/v1/affiliates/register', [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'company' => fake()->company,
            'email' => $email,
            'job_title' => fake()->jobTitle,
            'phone' => fake()->e164PhoneNumber,
            'partner_type' => AffiliatePartnerType::getRandomValue(),
            'more_info' => fake()->sentence,
        ]);

        $response->assertStatus(201);

        $response = $this->post('api/v1/affiliates/register', [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'company' => fake()->company,
            'email' => $email,
            'job_title' => fake()->jobTitle,
            'phone' => fake()->e164PhoneNumber,
            'partner_type' => AffiliatePartnerType::getRandomValue(),
            'more_info' => fake()->sentence,
        ]);

        $response->assertStatus(422);
    }
}
