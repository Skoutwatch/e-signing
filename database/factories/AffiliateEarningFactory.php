<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AffiliateEarning>
 */
class AffiliateEarningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var Affiliate $affiliate */
        $affiliate = Affiliate::factory()->create();

        /** @var Plan $plan */
        $plan = Plan::where('amount', '>', 0)
            ->inRandomOrder()
            ->first();

        return [
            'affiliate_id' => $affiliate->id,
            'payable_id' => $plan->id,
            'payable_type' => get_class($plan),
            'user_id' => (User::factory()->create())->id,
            'amount' => $plan->amount * 0.15,
        ];
    }
}
