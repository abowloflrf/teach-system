<?php

namespace App\Http\Middleware;

use Closure;

class AllowStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if (!auth()->check())
            return response('Unauthorized', 401);
        else if (auth()->user()->role != 1)
            return response('You are not allowed to do this', 403);
        return $next($request);
    }
}
