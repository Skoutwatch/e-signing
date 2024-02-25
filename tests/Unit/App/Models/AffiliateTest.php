<?php

namespace Tests\Unit\App\Models;

use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function discount_type_string_is_string()
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $affiliate->refresh();

        $this->assertInstanceOf(Affiliate::class, $affiliate);

        $this->assertIsString($affiliate->discount_type_string);
    }

    /**
     * @test
     */
    public function partner_type_string_is_string()
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $affiliate->refresh();

        $this->assertIsString($affiliate->partner_type_string);
    }

    /**
     * @test
     */
    public function affiliate_user_is_user()
    {
        $this->seed();

        Affiliate::factory()->create();
        $affiliate = Affiliate::first();

        $affiliate->refresh();

        $this->assertInstanceOf(User::class, $affiliate->user);
    }
}
