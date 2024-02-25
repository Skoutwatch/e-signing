<?php

namespace Database\Factories;

use App\Models\PaymentGateway;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPaymentGateway>
 */
class UserPaymentGatewayFactory extends Factory
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
            'payment_gateway_id' => PaymentGateway::all()->random()->id,
        ];
    }
}
