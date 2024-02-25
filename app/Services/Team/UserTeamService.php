<?php

namespace App\Services\Team;

use App\Models\Team;
use App\Models\User;
use App\Traits\Api\ApiResponder;

class UserTeamService
{
    use ApiResponder;

    public function __construct(public User $user, public Team $team)
    {
    }

    public function findModelUserTeam($model)
    {
        return $model::where('team_id', $this->team->id)
            ->where('user_id', $this->user->id)
            ->first();

    }

    public function createModel($model, $permission)
    {
        $teamdetails = $model::create([
            'user_id' => $this->user->id,
            'permission' => $permission,
            'team_id' => $this->team->id,
            'active' => true,
        ]);
    }
}
