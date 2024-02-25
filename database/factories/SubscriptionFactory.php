<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\Team;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'plan_id' => Plan::all()->random()->id,
            'canceled_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'expired_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'grace_days_ended_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'started_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'suppressed_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'was_switched' => $this->faker->boolean,
            'unit' => $this->faker->randomNumber(),
            'cancelled_subscription' => $this->faker->boolean,
            'occurrence' => $this->faker->word,
            'occurrence_limit' => $this->faker->randomNumber(),
            'deleted_at' => null,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'subscriber_type' => $this->faker->randomElement(['Team']),
            'subscriber_id' => $this->faker->randomElement([Team::all()->random()->id]),
            'transaction_id' => Transaction::all()->random()->id,
        ];
    }

    public function cancelled()
    {
        return $this->state([
            'cancelled_subscription' => true,
        ]);
    }
}
