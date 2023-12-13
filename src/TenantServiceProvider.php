<?php

namespace SteelAnts\LaravelTenant;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

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
        Event::listen(Login::class, AddSessionTenant::class);
        Event::listen(Logout::class, RemoveSessionTenant::class);

        $this->resolveSubdomainToTenant();
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // $this->publishes([
        //     __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
        // ]);
    }

    public function register()
    {
    }

    private function resolveSubdomainToTenant()
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $this->app->singleton(TenantManager::class, function () {
            $TenantModel = null;
            $TenantModel = Tenant::where('slug', explode(".", request()->getHost())[0]);
            $HostSegmentArray = explode(".", request()->getHost());
            $TenantSlug = $HostSegmentArray[count($HostSegmentArray) - 3];
            $TenantModel = Tenant::where('slug', $TenantSlug)
                ->with(['users'/*, 'settings'*/])
                ->first();

                if (is_null($TenantModel)) {
                    abort(404, 'Tenant ' . explode(".", request()->getHost())[0] . ' not found (' . request()->getHost() . ')');
                abort(404, 'Tenant ' . $TenantSlug . ' not found (' . request()->getHost() . ')');
                die();
            }

            return new TenantManager($TenantModel);
        });

        //Account for loading in login etc
        require_once(__DIR__.'/helpers.php');
    }
}
