<?php

namespace Tests\Unit\App\Models;

use App\Models\Affiliate;
use App\Models\AffiliateEarning;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateEarningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function affiliate_relation_is_instance_of_affiliate_model()
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $earning = $this->getEarning($affiliate);

        $this->assertInstanceOf(Affiliate::class, $earning->affiliate);
    }

    /**
     * @test
     */
    public function user_relation_is_instance_of_user_model()
    {
        $this->seed();

        User::factory()->create();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $earning = $this->getEarning($affiliate);

        $this->assertInstanceOf(User::class, $earning->user);
    }

    private function getEarning(Affiliate $affiliate): AffiliateEarning
    {
        /** @var User $user */
        User::factory()->count(2)->create();
        $user = User::inRandomOrder()
            ->first();

        $plan = Plan::where('amount', '>', 0)
            ->inRandomOrder()->first();

        $earning = new AffiliateEarning();
        $earning->affiliate_id = $affiliate->id;
        $earning->payable_id = $plan->id;
        $earning->payable_type = get_class($plan);
        $earning->amount = 0.15 * $plan->amount;
        $earning->user_id = $user->id;
        $earning->save();

        return $earning;
    }
}
