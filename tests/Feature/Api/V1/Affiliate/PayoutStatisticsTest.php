<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Enums\AffiliatePayoutStatus;
use App\Models\Affiliate;
use App\Models\AffiliateEarning;
use App\Models\AffiliatePayout;
use App\Models\BankDetail;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayoutStatisticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function returns_empty_data(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->get('api/v1/affiliates/payouts/statistics');

        $response->assertStatus(200);

        $response->assertJson(['unpaid' => 0, 'paid' => 0, 'total' => 0]);
    }

    /**
     * @test
     */
    public function returns_values(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        /** @var Plan $plan */
        $plan = Plan::where('amount', '>', 0)
            ->inRandomOrder()
            ->first();

        /** @var BankDetail $bankDetails */
        BankDetail::factory()->create();
        $bankDetails = BankDetail::first();

        AffiliateEarning::create([
            'affiliate_id' => $affiliate->id,
            'payable_id' => $plan->id,
            'payable_type' => get_class($plan),
            'user_id' => (User::factory()->create())->id,
            'amount' => 600,
        ]);

        AffiliateEarning::create([
            'affiliate_id' => $affiliate->id,
            'payable_id' => $plan->id,
            'payable_type' => get_class($plan),
            'user_id' => (User::factory()->create())->id,
            'amount' => 450,
        ]);

        $earnings = 600 + 450;

        AffiliatePayout::create([
            'affiliate_id' => $affiliate->id,
            'bank_detail_id' => $bankDetails->id,
            'amount' => 300,
            'status' => AffiliatePayoutStatus::Paid,
        ]);

        AffiliatePayout::create([
            'affiliate_id' => $affiliate->id,
            'bank_detail_id' => $bankDetails->id,
            'amount' => 300,
            'status' => AffiliatePayoutStatus::Pending,
        ]);

        $payouts = 300;

        $this->actingAs($affiliate->user);

        $response = $this->get('api/v1/affiliates/payouts/statistics');

        $response->assertStatus(200);

        $response->assertJson([
            'unpaid' => number_format($earnings - $payouts, 2),
            'paid' => number_format($payouts, 2),
            'total' => number_format($earnings, 2),
        ]);
    }
}
