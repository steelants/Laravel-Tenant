<?php

namespace SteelAnts\LaravelTenant;

use Exception;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

use SteelAnts\LaravelTenant\Services\TenantManager;
use SteelAnts\LaravelTenant\Listeners\AddSessionTenant;
use SteelAnts\LaravelTenant\Listeners\RemoveSessionTenant;
use SteelAnts\LaravelTenant\Middleware\HasTenant;
use SteelAnts\LaravelTenant\Middleware\DontHaveTenant;


class TenantServiceProvider extends ServiceProvider
{
    public function boot(Request $request)
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

        Event::listen(Login::class, AddSessionTenant::class);
        Event::listen(Logout::class, RemoveSessionTenant::class);

        $this->app->make('router')->aliasMiddleware('has-tenant', HasTenant::class);
        $this->app->make('router')->aliasMiddleware('dont-have-tenant', DontHaveTenant::class);

        if (!$this->app->runningInConsole()) {
            return;
        }

        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/tenant.php' => config_path('tenant.php'),
        ], 'tenant-config');

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'tenant-migrations');
    }

    public function register()
    {
    }

    private function resolveSubdomainToTenant(Request $request)
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $this->app->singleton(TenantManager::class, function () use ($request) {
            $tenantModel = null;
            $slug = trim(str_replace(trim(config('app.url'), '.'), "", $request->getHost()), '.');
            $tenantModel = (config('tennat.tenant_model'))::where('slug', $slug)
                ->with(['users'/*, 'settings'*/])
                ->first();

            return new TenantManager($tenantModel);
        });
    }

    private function resolvePathToTenant(Request $request)
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $this->app->singleton(TenantManager::class, function () use ($request) {
            $tenantModel = null;
            $slug =  $request->route('tenant');
            $tenantModel = (config('tennat.tenant_model'))::where('slug', $slug)
                ->with(['users'/*, 'settings'*/])
                ->first();

            return new TenantManager($tenantModel);
        });
    }

    private function resolveTenantFromSession()
    {
        if ($this->app->runningInConsole() || !session()->has('tenant_id')) {
            return;
        }

        $this->app->singleton(TenantManager::class, function () {
            $tenantModel = null;
            $tenantModel = (config('tennat.tenant_model'))::find(session()->get('tenant_id'))
                ->with(['users'/*, 'settings'*/])
                ->first();

            return new TenantManager($tenantModel);
        });
    }
}
