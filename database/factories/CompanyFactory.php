<?php

namespace Database\Factories;

use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'company_name' => $this->faker->catchPhrase(),
            'type' => $this->faker->catchPhrase(),
            'registration_company_number' => $this->faker->numerify('##########'),
            'phone' => $this->faker->e164PhoneNumber(),
            'email' => User::all()->random()->email,
            'address' => $this->faker->address(),
            'country_id' => Country::all()->random()->id,
            'state_id' => State::all()->random()->id,
            'city_id' => City::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];
    }
}
