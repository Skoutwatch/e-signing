<?php

namespace Database\Factories;

use App\Models\PaymentGateway;
use App\Models\PaymentGatewayList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $charges = $this->faker->numberBetween($min = 1500, $max = 6000);
        $amount_paid = $this->faker->numberBetween($min = 1500, $max = 6000);
        $payment_gateway_charge = $this->faker->numberBetween($min = 1500, $max = 6000);
        $discount_amount = $this->faker->numberBetween($min = 100, $max = 200);

        return [
            'title' => $this->faker->randomElement(['Plan Upgrade', 'Affidavit request', 'Notary request']),
            'user_id' => User::all()->random()->id,
            'payment_gateway_id' => PaymentGateway::all()->random()->id,
            'transactionable_type' => $this->faker->randomElement(['Plan', 'Request']),
            'subtotal' => ($charges + $amount_paid + $payment_gateway_charge) - $discount_amount,
            'total' => $charges + $amount_paid + $payment_gateway_charge + $discount_amount,
            'charges' => $charges,
            'amount_paid' => $amount_paid,
            'payment_reference' => $this->faker->uuid,
            'payment_gateway' => PaymentGatewayList::all()->random()->id,
            'payment_gateway_charge' => $payment_gateway_charge,
            'discount_applied' => 1,
            'discount_amount' => $discount_amount,
            'status' => $this->faker->randomElement(['Pending', 'New', 'Completed']),
        ];
    }
}
