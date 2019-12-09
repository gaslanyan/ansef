<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request,
                           Closure $next,
                           $permission)
    {
        $permissions = explode('|', $permission);
        if (checkPermission($permissions)) {
            return $next($request);
        }
        return response()->view('errors.check-role');
    }
}
