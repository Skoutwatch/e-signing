<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeletedTeamuser>
 */
class DeletedTeamUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'team_id' => Team::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'active' => false,
            'permission' => $this->faker->randomElement(['View', 'Admin', 'Send']),
        ];
    }
}