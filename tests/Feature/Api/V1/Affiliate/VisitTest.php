<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Models\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class VisitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function increment_affiliates_visit(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $response = $this->patch("api/v1/affiliates/visit-link/{$affiliate->code}");

        $response->assertStatus(204);

        $affiliate->refresh();

        $this->assertEquals(1, $affiliate->visits);
    }

    /**
     * @test
     */
    public function code_does_not_exist(): void
    {
        $this->seed();

        $code = date('U').Str::random();

        $response = $this->patch("api/v1/affiliates/visit-link/{$code}");

        $response->assertStatus(404);
    }
}
