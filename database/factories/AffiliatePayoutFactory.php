<?php

namespace Database\Factories;

use App\Enums\AffiliatePayoutStatus;
use App\Models\Affiliate;
use App\Models\Bank;
use App\Models\BankDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AffiliatePayout>
 */
class AffiliatePayoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (Affiliate::count() < 1) {
            Affiliate::factory()->create();
        }
        /** @var Affiliate $affiliate */
        $affiliate = Affiliate::first();

        /** @var BankDetail $bankDetail */
        $bankDetail = BankDetail::create([
            'user_id' => $affiliate->user_id,
            'bank_id' => Bank::all()->random()->id,
            'bank_account_name' => Bank::all()->random()->name,
            'bank_account_number' => $this->faker->numerify('##########'),
        ]);

        return [
            'affiliate_id' => $affiliate->id,
            'bank_detail_id' => $bankDetail->id,
            'amount' => $this->faker->numberBetween(1000, 20000),
            'status' => AffiliatePayoutStatus::Pending,
        ];
    }
}
