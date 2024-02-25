<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionReneal>
 */
class SubscriptionRenewalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'overdue' => $this->faker->randomElement(['0', '1']),
            'renewal' => $this->faker->randomElement(['0', '1']),
            'subscription_id' => Subscription::all()->random()->id,
        ];
    }
}
