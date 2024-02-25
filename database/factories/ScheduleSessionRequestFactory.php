<?php

namespace Database\Factories;

use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleSessionRequest>
 */
class ScheduleSessionRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'scheduled_session_id' => ScheduleSession::all()->random()->id,
            'notary_id' => User::all()->random()->id,
            'status' => $this->faker->randomElement(['Awaiting', 'Complete', 'New']),
        ];
    }
}
