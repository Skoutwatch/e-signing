<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mixpanel;
use Symfony\Component\HttpFoundation\Response;

class MixpanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //$mixpanel = new Mixpanel(config('services.mixpanel.token'));

        return $next($request);
    }
}
