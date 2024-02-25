<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankDetail>
 */
class BankDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'bank_id' => Bank::all()->random()->id,
            'bank_account_name' => Bank::all()->random()->name,
            'bank_account_number' => $this->faker->numerify('##########'),
        ];
    }
}
