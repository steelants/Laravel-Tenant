<?php

namespace SteelAnts\LaravelTenant\Middleware;

use Closure;
use Illuminate\Http\Request;

class DontHaveTenant
{
    public function handle(Request $request, Closure $next)
    {
        if (!isset(tenant()) || is_null(tenant())) {
            return $next($request);
        }

        abort(404, 'Page must be without tenant (' . tenant()->slug . ')');
        die();
    }
}
