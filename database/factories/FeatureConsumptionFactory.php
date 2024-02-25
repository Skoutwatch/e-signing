<?php

namespace Database\Factories;

use App\Models\Feature;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeatureConsumption>
 */
class FeatureConsumptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'consumption' => 1.0,
            'expired_at' => $this->faker->date,
            'feature_id' => Feature::all()->random()->id,
            'subscriber_type' => $this->faker->randomElement(['Team', 'User']),
            'subscriber_id' => $this->faker->randomElement([User::all()->random()->id, Team::all()->random()->id]),
        ];
    }
}
