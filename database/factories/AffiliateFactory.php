<?php

namespace Database\Factories;

use App\Models\User;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Affiliate>
 */
class AffiliateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (User::count() < 1) {
            User::factory()->create();
            $user = User::first();
        } else {
            $user = User::inRandomOrder()->first();
        }

        $service = new AffiliateService();

        return [
            'user_id' => $user->id,
            'code' => $service->generateUniqueCode($user->first_name),
        ];
    }
}
