<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserPlanController extends Controller
{
    public function show($planName)
    {
        $users = User::whereHas('activeTeam.team.subscription.plan', function ($query) use ($planName) {
            $query->where('name', $planName);
        })->get();

        if ($users->isEmpty()) {
            return $this->errorResponse('No users with the given plan name', 404);
        }

        return $this->showAll($users);
    }
}
