<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Enums\AffiliateSubscriberStatus;
use Tests\TestCase;

class SubscriberStatusTest extends TestCase
{
    /**
     * @test
     */
    public function returns_status_object(): void
    {
        $statuses = [];
        foreach (AffiliateSubscriberStatus::getKeys() as $key) {
            $value = AffiliateSubscriberStatus::getValue($key);
            $statuses[AffiliateSubscriberStatus::getDescription($value)] = $value;
        }
        $response = $this->get('api/v1/affiliates/subscriber-status');

        $response->assertOk();
        $response->assertJson([
            'statuses' => $statuses,
        ]);
    }
}
