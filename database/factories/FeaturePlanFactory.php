<?php

namespace Database\Factories;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeaturePlan>
 */
class FeaturePlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'charges' => $this->faker->numberBetween($min = 1500, $max = 6000),
            'feature_id' => Feature::all()->random()->id,
            'plan_id' => Plan::all()->random()->id,
        ];
    }
}
