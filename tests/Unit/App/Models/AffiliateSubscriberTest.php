<?php

namespace Tests\Unit\App\Models;

use App\Models\Affiliate;
use App\Models\AffiliateSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateSubscriberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function status_string_is_string(): void
    {
        $this->seed();
        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        User::factory()->count(2)->create();
        $user = User::inRandomOrder()->first();

        $subscriber = $this->getSubscriber($affiliate, $user);

        $this->assertIsString($subscriber->status_string);
    }

    /**
     * @test
     */
    public function affiliate_relation_is_affiliate_model(): void
    {
        $this->seed();
        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        User::factory()->count(2)->create();
        $user = User::inRandomOrder()->first();

        $subscriber = $this->getSubscriber($affiliate, $user);

        $this->assertInstanceOf(Affiliate::class, $subscriber->affiliate);
    }

    /**
     * @test
     */
    public function user_relation_is_user_model(): void
    {
        $this->seed();
        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        User::factory()->count(2)->create();
        $user = User::inRandomOrder()->first();

        $subscriber = $this->getSubscriber($affiliate, $user);

        $this->assertInstanceOf(User::class, $subscriber->user);
    }

    private function getSubscriber(Affiliate $affiliate, User $user): AffiliateSubscriber
    {
        $subscriber = new AffiliateSubscriber();
        $subscriber->affiliate_id = $affiliate->id;
        $subscriber->user_id = $user->id;
        $subscriber->joined_at = now();
        $subscriber->commission = random_int(300, 5000);
        $subscriber->save();

        $subscriber->refresh();

        return $subscriber;
    }
}
