<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard == "superadmin" &&  Auth::guard($guard)->check()) {
            return redirect('/superadmin');
        }
        if ($guard == "admin" &&  Auth::guard($guard)->check()) {
            return redirect('/admin');
        }
        if ($guard == "referee" && Auth::guard($guard)->check()) {
            return redirect('/referee');
        }
        if ($guard == "viewer" && Auth::guard($guard)->check()) {
            return redirect('/viewer');
        }
        if ($guard == "applicant" && Auth::guard($guard)->check()) {
            return redirect('/applicant');
        }

        return $next($request);
    }
}
