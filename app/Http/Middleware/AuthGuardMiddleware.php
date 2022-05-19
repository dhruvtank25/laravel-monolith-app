<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use View;

class AuthGuardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //$request->user()->authorizeRoles(['admin']);\\
        if (!Auth::guard($guard)->check()) {
            return redirect('/login');
        }
        View::addLocation(base_path() . '/resources/views/'.$guard);
        return $next($request);
    }
}
