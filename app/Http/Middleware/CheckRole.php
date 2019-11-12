<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request,
                           Closure $next,
                           $permission)
    {
//dd($request);
        $permission = explode('|', $permission);
        if (checkPermission($permission)) {
            return $next($request);
        }
        return response()->view('errors.check-role');
    }
}
