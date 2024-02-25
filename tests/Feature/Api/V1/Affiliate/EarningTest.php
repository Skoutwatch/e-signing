<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Models\Affiliate;
use App\Models\AffiliateEarning;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EarningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function no_data_returned(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();
        $this->actingAs($affiliate->user);

        $response = $this->get('/api/v1/affiliates/earnings');

        $response->assertOk();
        $response->assertJson([]);
    }

    /**
     * @test
     */
    public function keyword_criteria(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();
        $this->actingAs($affiliate->user);

        User::factory()->count(3)->create();
        $plan = Plan::where('amount', '>', 0)
            ->inRandomOrder()
            ->firstOrFail();

        foreach (User::all() as $user /** @var User $user */) {
            AffiliateEarning::create([
                'affiliate_id' => $affiliate->id,
                'payable_id' => $plan->id,
                'payable_type' => get_class($plan),
                'user_id' => $user->id,
                'amount' => 0.15 * $plan->amount,
            ]);
        }

        $earning = AffiliateEarning::with('user')
            ->first();

        $this->actingAs($affiliate->user);

        $response = $this->json('get', 'api/v1/affiliates/earnings', [
            'keyword' => $earning->user->first_name,
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

        AffiliateEarning::factory()->count(30)->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->json('get', 'api/v1/affiliates/earnings', [
            'page' => 2,
        ]);

        $response->assertOk();

        $data = $response->json();

        $this->assertEquals(2, $data['meta']['current_page']);
    }
}
