<?php

namespace Database\Factories;

use App\Models\NotarySchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotarySchedule>
 */
class NotaryScheduleFactory extends Factory
{
    protected $model = NotarySchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'date' => $this->faker->dateTimeThisMonth(),
            'start_time' => Carbon::now()->format('h:i:s'),
            'end_time' => Carbon::now()->format('h:i:s')->addHour(),
        ];
    }
}
