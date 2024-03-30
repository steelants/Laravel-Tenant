<?php

namespace SteelAnts\LaravelTenant\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('tenant_id')) {
            $this->resolveTenantFromSession();
        } else {
            switch (config('tenant.resolver', 'subdomain')) {
                case 'subdomain':
                    $this->resolveSubdomainToTenant($request);
                    break;

                case 'path':
                    $this->resolvePathToTenant($request);
                    break;

                case 'session':
                    $this->resolveTenantFromSession();
                    break;

                default:
                    throw new Exception("Tenant resolver not defined !!!");
                    break;
            }
        }

        return $next($request);
    }

    private function resolveSubdomainToTenant(Request $request)
    {
        $slug = trim(str_replace(trim(config('app.url'), '.'), "", $request->getHost()), '.');
        $tenant = (config('tenant.tenant_model'))::with(['users'/*, 'settings'*/])
            ->where('slug', $slug)
            ->first();

        tenantManager()->set($tenant);
    }

    private function resolvePathToTenant(Request $request)
    {
        $slug =  $request->route('tenant');
        $tenant = (config('tenant.tenant_model'))::with(['users'/*, 'settings'*/])
            ->where('slug', $slug)
            ->first();

        tenantManager()->set($tenant);
    }

    private function resolveTenantFromSession()
    {
        $tenant = null;

        if (session()->has('tenant_id')) {
            $tenant = (config('tenant.tenant_model'))::with(['users'/*, 'settings'*/])
                ->find(session()->get('tenant_id'));
        }

        tenantManager()->set($tenant);
    }
}
