<?php

namespace Api\V1\Affiliate;

use App\Models\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardGraphTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function default_period(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->json('GET', '/api/v1/affiliates/dashboard/graph');

        $response->assertOk();

        $response->assertJson([]);
    }
}
