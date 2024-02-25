<?php

namespace Database\Factories;

use App\Models\Feature;
use App\Models\FeaturePlan;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeatureTicket>
 */
class FeatureTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'charges' => FeaturePlan::all()->random()->charges,
            'expired_at' => $this->faker->date(),
            'feature_id' => Feature::all()->random()->id,
            'subscriber_type' => $this->faker->randomElement(['Team', 'User']),
            'subscriber_id' => $this->faker->randomElement([User::all()->random()->id, Team::all()->random()->id]),
        ];
    }
}
