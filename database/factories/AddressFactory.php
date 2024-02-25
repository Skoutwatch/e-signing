<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => User::all()->random()->id,
            'name' => User::all()->random()->name,
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'city_id' => City::all()->random()->id,
            'country_id' => Country::all()->random()->id,
            'state_id' => State::all()->random()->id,
        ];
    }
}
