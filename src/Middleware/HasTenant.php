<?php

namespace SteelAnts\LaravelTenant\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasTenant
{
    public function handle(Request $request, Closure $next)
    {
        if (!is_null(tenant())) {
            return $next($request);
        }

        abort(404, 'Tenant not found (' . request()->getHost() . ')');
        die();
    }
}
