<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Enums\AffiliatePartnerType;
use Tests\TestCase;

class PartnerTypeTest extends TestCase
{
    /**
     * @test
     */
    public function get_partner_types(): void
    {
        $types = [];
        foreach (AffiliatePartnerType::getKeys() as $key) {
            $value = AffiliatePartnerType::getValue($key);
            $types[AffiliatePartnerType::getDescription($value)] = $value;
        }

        $response = $this->get('api/v1/affiliates/partner-types');

        $response->assertOk();
        $response->assertJson([
            'types' => $types,
        ]);
    }
}
