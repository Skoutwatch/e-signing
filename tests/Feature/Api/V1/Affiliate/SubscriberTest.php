<?php

namespace Tests\Feature\Api\V1\Affiliate;

use App\Enums\AffiliateSubscriberStatus;
use App\Models\Affiliate;
use App\Models\AffiliateSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function returns_data(): void
    {
        $this->seed();

        AffiliateSubscriber::factory()->count(30)->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->get('api/v1/affiliates/subscribers');

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

        $response = $this->get('api/v1/affiliates/subscribers');

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function check_status_parameter(): void
    {
        $this->seed();

        AffiliateSubscriber::factory()->count(30)->create();
        $affiliate = Affiliate::first();

        $subscriber = AffiliateSubscriber::first();
        $subscriber->status = AffiliateSubscriberStatus::Paying;
        $subscriber->save();

        $this->actingAs($affiliate->user);

        $response = $this->json('get', 'api/v1/affiliates/subscribers', [
            'status' => AffiliateSubscriberStatus::Paying,
        ]);

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    /**
     * @test
     */
    public function send_keyword_parameter(): void
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        User::factory()->count(3)->create();

        foreach (User::all() as $user /** @var User $user */) {
            AffiliateSubscriber::create([
                'affiliate_id' => $affiliate->id,
                'user_id' => $user->id,
                'joined_at' => now(),
                'commission' => random_int(500, 5000),
            ]);
        }

        $subscriber = AffiliateSubscriber::first();

        $this->actingAs($affiliate->user);

        $response = $this->json('get', 'api/v1/affiliates/subscribers', [
            'keyword' => $subscriber->user->first_name,
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

        AffiliateSubscriber::factory()->count(30)->create();
        $affiliate = Affiliate::first();

        $this->actingAs($affiliate->user);

        $response = $this->json('get', 'api/v1/affiliates/subscribers', [
            'page' => 2,
        ]);

        $response->assertOk();

        $data = $response->json();

        $this->assertEquals(2, $data['meta']['current_page']);
    }
}
