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
            $Slug = trim(str_replace(trim(config('app.url'),'.'), "", request()->getHost()),'.');
            $TenantModel = Tenant::where('slug', $Slug)
                ->with(['users'/*, 'settings'*/])
                ->first();

            if (is_null($TenantModel)) {
                abort(404, 'Tenant ' . $Slug . ' not found (' . request()->getHost() . ')');
                die();
            }

            return new TenantManager($TenantModel);
        });

        //Account for loading in login etc.
        //TODO: Need Investigation
        require_once(__DIR__.'/helpers.php');
    }
}
