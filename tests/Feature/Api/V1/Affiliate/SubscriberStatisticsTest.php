<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Models\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberStatisticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function returns_data(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->get('api/v1/affiliates/subscribers/statistics');

        $response->assertStatus(200);

        $response->assertJson(['converted' => 0, 'visit' => 0, 'conversion' => 0]);
    }
}
