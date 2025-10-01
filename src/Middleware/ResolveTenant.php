<?php

namespace SteelAnts\LaravelTenant\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
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

            case 'static':
                $this->resolveStaticTenant();
                break;

            default:
                throw new Exception("Tenant resolver not defined !!!");
                break;
        }

        return $next($request);
    }

    private function resolveSubdomainToTenant(Request $request): Model
    {
        $appDomainRootWithoutPort = str_replace(":". $request->getPort(), "", trim(config('app.url'), '.'));
        $slug = trim(str_replace($appDomainRootWithoutPort, "", $request->getHost()), '.');
        $tenant = (config('tenant.tenant_model'))::with(['users'/*, 'settings'*/])
            ->where('slug', $slug)
            ->first();

        tenantManager()->set($tenant);
        return $tenant;
    }

    private function resolvePathToTenant(Request $request): Model
    {
        $slug = $request->route('tenant');
        $tenant = (config('tenant.tenant_model'))::with(['users'/*, 'settings'*/])
            ->where('slug', $slug)
            ->first();

        tenantManager()->set($tenant);
        return $tenant;
    }

    private function resolveTenantFromSession()
    {
        $tenant = null;

        if (session()->has('tenant_id')) {
            $tenant = (config('tenant.tenant_model'))::with(['users'/*, 'settings'*/])
                ->find(session()->get('tenant_id'));
        }

        tenantManager()->set($tenant);
        return $tenant;
    }

    private function resolveStaticTenant()
    {
        $tenant = (config('tenant.tenant_model'))::with(['users'/*, 'settings'*/])
            ->find(config('tenant.tenant_id'));

        tenantManager()->set($tenant);
    }
}
