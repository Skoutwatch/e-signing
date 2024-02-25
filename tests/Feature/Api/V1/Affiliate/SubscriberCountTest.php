<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Models\Affiliate;
use App\Models\AffiliateSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberCountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_is_not_affiliate(): void
    {
        $this->seed();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('api/v1/affiliates/subscriber-count');

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_has_no_subscribers(): void
    {
        $this->seed();
        Affiliate::factory()->create();
        $affiliate = Affiliate::first();
        $this->actingAs($affiliate->user);

        $response = $this->get('api/v1/affiliates/subscriber-count');

        $response->assertOk();
        $response->json([
            'subscribers' => 0,
        ]);
    }

    /**
     * @test
     */
    public function user_has_subscribers(): void
    {
        $this->seed();
        Affiliate::factory()->create();
        $affiliate = Affiliate::first();
        $this->actingAs($affiliate->user);

        User::factory()->count(5)->create();

        $users = User::where('id', '<>', $affiliate->user_id)
            ->get();

        foreach ($users as $user /** @var User $user */) {
            AffiliateSubscriber::create([
                'affiliate_id' => $affiliate->id,
                'user_id' => $user->id,
                'joined_at' => now(),
                'commission' => fake()->randomFloat(min: 500, max: 10000),
            ]);
        }

        $response = $this->get('api/v1/affiliates/subscriber-count');

        $response->assertOk();
        $response->json([
            'subscribers' => $affiliate->subscribers->count(),
        ]);
    }
}
