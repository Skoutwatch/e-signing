<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Services\Affiliate\AffiliateService;
use Tests\TestCase;

class PromoKitTest extends TestCase
{
    /**
     * @test
     */
    public function kit_url_is_returned(): void
    {
        $response = $this->get('api/v1/affiliates/promo-kit');
        $response->assertOk();
        $response->assertJson([
            'kit_url' => AffiliateService::promoKitUrl(),
        ]);
    }
}
