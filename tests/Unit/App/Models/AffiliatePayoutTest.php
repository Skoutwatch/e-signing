<?php

namespace Tests\Unit\App\Models;

use App\Models\Affiliate;
use App\Models\AffiliatePayout;
use App\Models\BankDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliatePayoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function status_string_is_string()
    {
        $this->seed();

        User::factory()->count(1)->create();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $payout = $this->getPayout($affiliate);

        $this->assertIsString($payout->status_string);
    }

    /**
     * @test
     */
    public function affiliate_relation_is_affiliate_model()
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $payout = $this->getPayout($affiliate);

        $this->assertInstanceOf(Affiliate::class, $payout->affiliate);
    }

    /**
     * @test
     */
    public function bank_detail_relation_is_bank_detail_model()
    {
        $this->seed();

        User::factory()->count(1)->create();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $payout = $this->getPayout($affiliate);

        $this->assertInstanceOf(BankDetail::class, $payout->bankDetail);
    }

    /**
     * @throws \Exception
     */
    private function getPayout(Affiliate $affiliate): AffiliatePayout
    {
        BankDetail::factory()->create();
        $bankDetail = BankDetail::first();

        $payout = new AffiliatePayout();
        $payout->affiliate_id = $affiliate->id;
        $payout->bank_detail_id = $bankDetail->id;
        $payout->amount = random_int(300, 5000);
        $payout->save();

        $payout->refresh();

        return $payout;
    }
}
