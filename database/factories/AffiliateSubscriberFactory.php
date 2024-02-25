<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AffiliateSubscriber>
 */
class AffiliateSubscriberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (User::count() <= 1) {
            User::factory()->count(50)->create();
        }

        if (Affiliate::count() < 1) {
            Affiliate::factory()->create();
        }

        $affiliate = Affiliate::first();

        $plan = Plan::where('amount', '>', 0)
            ->first();

        return [
            'affiliate_id' => $affiliate?->id,
            'user_id' => (User::inRandomOrder()->first())->id,
            'plan_id' => $plan->id,
            'plan_type' => get_class($plan),
            'joined_at' => now(),
            'commission' => $plan->amount * 0.25,
        ];
    }
}
