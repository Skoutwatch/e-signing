<?php

namespace Tests\Feature\Admin;

use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_get_all_data_counts()
    {
        $response = $this->get('info');
        $response->assertStatus(200);

        /*$admin = User::factory()->create([
            'role' => 'Admin',
        ]);
        User::factory()->create();

        $response = $this->actingAs($admin)
            ->getJson(route('dashboard.data'));
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'message' => [
                        // 'users' => $users->count(),
                    ],

                ],
            ]);*/
    }

    // public function test_show_all_user_data()
    // {
    //     $admin = User::factory()->create([
    //         'role' => 'Admin',
    //     ]);
    //     $user = User::factory()->create();
    //     $response = $this->actingAs($admin)
    //     ->getJson(route('all.users.data'));
    //     $response->assertStatus(200)
    //     ->assertJson([
    //         'data' => [
    //            'message' => [
    //             'users' => $user,
    //            ]

    //         ]
    //     ]);
    // }

    // public function test_show_all_requests()
    // {
    //     $admin = User::factory()->create([
    //             'role' => 'Admin',
    //         ]);
    //         $requests = ScheduleSession::factory()->create();
    //         // dd($requests);
    //         $response = $this->actingAs($admin)
    //         ->getJson(route('all.requests'));
    //         $response->assertStatus(200)
    //         ->assertJson([
    //             'data' => [
    //                 'message' => [
    //                     [
    //                         'requests' => [

    //                         ],
    //                         'recent_requests' => []
    //                     ]

    //                 ]

    //             ]
    //         ]);
    // }

    public function test_show_one_requests_plus_participants()
    {
        $this->withoutExceptionHandling();
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);
        $request = ScheduleSession::factory()->create();

        $response = $this->actingAs($admin)
            ->getJson(route('one.request', parameters: ['scheduleSessionId' => $request->id]));
        $response->assertStatus(200);
    }
}
