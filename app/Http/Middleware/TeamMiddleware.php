<?php

namespace App\Http\Middleware;

use App\Models\TeamUser;
use Closure;
use Illuminate\Http\Request;

class TeamMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth('api')->user()->activeTeam == null) {
            $teamUsers = TeamUser::where('user_id', auth('api')->id())->get();

            foreach ($teamUsers as $team) {
                $findTeam = TeamUser::find($team->id);
                $findTeam->active = 0;
                $findTeam->save();
            }

            $findUserTeam = TeamUser::where('user_id', auth('api')->id())
                ->where('team_id', auth('api')->user()->team->id)
                ->first();

            $findUserTeam->active = 1;
            $findUserTeam->save();
        }

        return $next($request);
    }
}
