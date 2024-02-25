<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Models\Affiliate;
use App\Services\Affiliate\AffiliateService;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function get_affiliate_returns_affiliate_model(): void
    {
        $this->seed();

        // Create a mock Affiliate instance
        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        // Set up the request with a user and affiliate
        $request = HttpRequest::create('/api/v1/affiliates');
        $request->setUserResolver(function () use ($affiliate) {
            return (object) ['user' => $affiliate->user];
        });
        Request::swap($request);

        // Create an instance of a class using the AffiliateTrait (for testing purposes)
        $class = new class
        {
            use AffiliateTrait;

            public function testGetAffiliateMethod(): Affiliate
            {
                $this->getAffiliate();

                return $this->affiliate;
            }
        };

        $result = $class->testGetAffiliateMethod();

        $this->assertSame($affiliate->id, $result->id);
    }

    /**
     * @test
     */
    public function endpoint_returns_data(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();
        $service = new AffiliateService();

        $this->actingAs($affiliate->user);

        $response = $this->get('/api/v1/affiliates');

        $response->assertStatus(200);

        $response->assertJson([
            'analytics' => [
                'subscribers' => 0,
                'total_earnings' => null,
                'paid_earnings' => 0,
                'unpaid_earnings' => 0,
            ],
            'referral_url' => $service->referralUrl($affiliate),
            'recent_subscribers' => [],
            'earnings_data' => [],
        ]);
    }
}
