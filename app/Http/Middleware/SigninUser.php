<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class SigninUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($_COOKIE['c_user'])) {
            if ($request->session()->has('u_id')) {
                Redirect::to('/')->send();
            }
        }
             return $next($request);
    }
}
