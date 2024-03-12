<?php

namespace SteelAnts\LaravelTenant;

use Exception;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

use Illuminate\Http\Request;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

use SteelAnts\LaravelTenant\Models\Tenant;
use SteelAnts\LaravelTenant\Services\TenantManager;
use SteelAnts\LaravelTenant\Listeners\AddSessionTenant;
use SteelAnts\LaravelTenant\Listeners\RemoveSessionTenant;
use SteelAnts\LaravelTenant\Middleware\HasTenant;
use SteelAnts\LaravelTenant\Middleware\DontHaveTenant;


class TenantServiceProvider extends ServiceProvider
{
    public function boot(Request $request)
    {
        Event::listen(Login::class, AddSessionTenant::class);
        Event::listen(Logout::class, RemoveSessionTenant::class);

        switch (config('app.url', 'subdomain')) {
            case 'subdomain':
                $this->resolveSubdomainToTenant($request);
                break;

            case 'path':
                $this->resolvePathToTenant($request);
                break;

            default:
                throw new Exception("Tenant resolver not defined !!!");
                break;
        }

        $this->app->make('router')->aliasMiddleware('has-tenant', HasTenant::class);
        $this->app->make('router')->aliasMiddleware('dont-have-tenant', DontHaveTenant::class);

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            //     __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
            __DIR__ . '/../config/tenant.php' => config_path('tenant.php'),
        ]);
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
            $TenantModel = null;
            $Slug = trim(str_replace(trim(config('app.url'), '.'), "", $request->getHost()), '.');
            $TenantModel = Tenant::where('slug', $Slug)
                ->with(['users'/*, 'settings'*/])
                ->first();

            return new TenantManager($TenantModel);
        });

        //Account for loading in login etc.
        //TODO: Need Investigation
        require_once(__DIR__ . '/helpers.php');
    }

    private function resolvePathToTenant(Request $request)
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $this->app->singleton(TenantManager::class, function () use ($request) {
            $TenantModel = null;
            $Slug =  $request->route('tenant');
            $TenantModel = Tenant::where('slug', $Slug)
                ->with(['users'/*, 'settings'*/])
                ->first();

            return new TenantManager($TenantModel);
        });

        //Account for loading in login etc.
        //TODO: Need Investigation
        require_once(__DIR__ . '/helpers.php');
    }
}
