<?php

namespace SteelAnts\LaravelTenant;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

use SteelAnts\LaravelTenant\Models\Tenant;
use SteelAnts\LaravelTenant\Services\TenantManager;
use SteelAnts\LaravelTenant\Listeners\AddSessionTenant;
use SteelAnts\LaravelTenant\Listeners\RemoveSessionTenant;



class TenantServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //Event::listen(\Illuminate\Auth\Events\Login::class, AddSessionTenant::class);
        //Event::listen(\Illuminate\Auth\Events\Logout::class, RemoveSessionTenant::class);

        if (!function_exists('tenantManager')) {
            /** @return TenantManager */
            function tenantManager()
            {
                return app(TenantManager::class);
            }
        }

        if (!function_exists('tenant')) {
            /** @return Tenant */
            function tenant()
            {
                return app(TenantManager::class)->getTenant();
            }
        }

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/boilerplate'),
            __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
        ]);
    }

    public function register()
    {
    }
}
