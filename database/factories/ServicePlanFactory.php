<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServicePlan>
 */
class ServicePlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $counter = 1;
        $additional_unit_price = $this->faker->numberBetween($min = 100, $max = 200);
        $notary_fee_cost = $this->faker->numberBetween($min = 1500, $max = 6000);
        $regulatory_fee_cost = $this->faker->numberBetween($min = 100, $max = 500);
        $other_partners_cost = $this->faker->numberBetween($min = 100, $max = 500);
        $tonote_service_cost = $this->faker->numberBetween($min = 500, $max = 1500);
        $agora_cost = $this->faker->numberBetween($min = 100, $max = 200);
        $aws_cost = $this->faker->numberBetween($min = 100, $max = 200);
        $email_cost = $this->faker->numberBetween($min = 100, $max = 200);
        $customer_support_cost = $this->faker->numberBetween($min = 100, $max = 200);
        $verifyme_cost = $this->faker->numberBetween($min = 100, $max = 200);
        $payment_processing_cost = $this->faker->numberBetween($min = 100, $max = 200);

        return [
            'name' => $this->faker->randomElement(['Affidavits', 'Notary']),
            'unit_count' => $counter++,
            'apply_additional_price' => $this->faker->randomElement(['1', '0']),
            'additional_unit_price' => $additional_unit_price,
            'additional_price_measured_unit' => $this->faker->randomElement(['1', '0']),
            'start_business_time' => '05:00:00',
            'end_business_time' => '17:00:00',
            'session_minutes_time' => '15',
            'notary_fee_cost' => $notary_fee_cost,
            'regulatory_fee_cost' => $regulatory_fee_cost,
            'other_partners_cost' => $other_partners_cost,
            'tonote_service_cost' => $tonote_service_cost,
            'agora_cost' => $agora_cost,
            'aws_cost' => $aws_cost,
            'email_cost' => $email_cost,
            'customer_support_cost' => $customer_support_cost,
            'verifyme_cost' => $verifyme_cost,
            'payment_processing_cost' => $payment_processing_cost,
            'total' => ($additional_unit_price + $notary_fee_cost + $regulatory_fee_cost + $other_partners_cost + $tonote_service_cost + $agora_cost + $aws_cost + $email_cost + $customer_support_cost + $verifyme_cost + $payment_processing_cost),
        ];
    }
}
