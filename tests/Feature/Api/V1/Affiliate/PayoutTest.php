<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Enums\AffiliatePayoutStatus;
use App\Models\Affiliate;
use App\Models\AffiliatePayout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function returns_data(): void
    {
        $this->seed();

        AffiliatePayout::factory()->count(30)->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->get('api/v1/affiliates/payouts');

        $response->assertOk();
        $response->assertJsonCount(15, 'data');
    }

    /**
     * @test
     */
    public function returns_empty_data(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->get('api/v1/affiliates/payouts');

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function check_status_parameter(): void
    {
        $this->seed();

        AffiliatePayout::factory()->count(30)->create();
        $affiliate = Affiliate::first();

        $payout = AffiliatePayout::first();
        $payout->status = AffiliatePayoutStatus::Paid;
        $payout->save();

        $this->actingAs($affiliate->user);

        $response = $this->json('get', 'api/v1/affiliates/payouts', [
            'status' => AffiliatePayoutStatus::Paid,
        ]);

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    /**
     * @test
     */
    public function has_page_two(): void
    {
        $this->seed();

        AffiliatePayout::factory()->count(30)->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->json('get', 'api/v1/affiliates/payouts', [
            'page' => 2,
        ]);

        $response->assertOk();

        $data = $response->json();

        $this->assertEquals(2, $data['meta']['current_page']);
    }
}
