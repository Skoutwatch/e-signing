<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\Api\ApiResponder;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class PlanChangeMiddleware
{
    use ApiResponder;

    public function handle(Request $request, Closure $next)
    {
        if (auth('api')->user() == null) {
            return $this->errorResponse('Unauthenticated', 401);
        }

        User::find(auth('api')->id())->update(['last_activity' => Carbon::now()->format('Y-m-d H:i:s')]);

        return $next($request);
    }
}
