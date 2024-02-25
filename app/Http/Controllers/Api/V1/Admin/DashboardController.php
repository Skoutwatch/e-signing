<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use App\Models\Subscription;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $notaryCount = User::where('role', 'Notary')->get()->count();
        $CFO = User::where('role', 'CFO')->get()->count();
        $allRequests = ScheduleSession::count();
        $allSubscriptions = Subscription::where('expired_at', null)->get()->count();

        $data = [
            'users' => $userCount,
            'notaries' => $notaryCount,
            'CFO' => $CFO,
            'requests' => $allRequests,
            'subscriptions' => $allSubscriptions,
            'sent_ocuments' => 50,
            'seals' => 20,
            'in-house notaries' => 20,
            'revenue' => [
                'amount' => [100, 200, 300],
                'months' => [
                    'january', 'february',
                    'march', 'april',
                    'may', 'june',
                    'july', 'august',
                    'september', 'october',
                    'november', 'december',
                ],

            ],
        ];

        return $this->showMessage($data);
    }

    public function show()
    {
        $users = User::with(['activeTeam.team.subscription.plan'])->get();

        $userData = [];

        foreach ($users as $user) {
            $activeTeam = $user->activeTeam;

            $planName = null;

            if ($activeTeam) {
                $subscription = $activeTeam->subscription;

                if ($subscription) {
                    $plan = $subscription->plan;

                    if ($plan) {
                        $planName = $plan->name;
                    }
                }
            }

            $userData[] = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'system_verification' => $user->system_verification,
                'plan_name' => $planName,
            ];
        }

        $data = [
            'users' => $userData,
        ];

        return $this->showMessage($data);
    }
}
