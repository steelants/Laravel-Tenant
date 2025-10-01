<?php

namespace SteelAnts\LaravelTenant;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SteelAnts\LaravelTenant\Listeners\AddSessionTenant;
use SteelAnts\LaravelTenant\Listeners\RemoveSessionTenant;
use SteelAnts\LaravelTenant\Middleware\DontHaveTenant;
use SteelAnts\LaravelTenant\Middleware\HasTenant;
use SteelAnts\LaravelTenant\Middleware\ResolveTenant;
use SteelAnts\LaravelTenant\Services\TenantManager;

class TenantServiceProvider extends ServiceProvider
{
    public function boot(Request $request)
    {
        $this->app->singleton(TenantManager::class, function () {
            return new TenantManager(null);
        });

        Event::listen(Login::class, AddSessionTenant::class);
        Event::listen(Logout::class, RemoveSessionTenant::class);

        $this->app->make('router')->aliasMiddleware('resolve-tenant', ResolveTenant::class);
        $this->app->make('router')->aliasMiddleware('has-tenant', HasTenant::class);
        $this->app->make('router')->aliasMiddleware('dont-have-tenant', DontHaveTenant::class);

        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', ResolveTenant::class);

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

        // TODO
        // $this->loadRoutesFrom(__DIR__ . '/../assets/routes.php');

        // $this->publishes([
        //     __DIR__ . '/../assets/tenant.stub.php' => base_path('routes/tenant.php'),
        // ], 'routes');
    }

    public function register()
    {
    }
}
