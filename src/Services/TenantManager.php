<?php

namespace SteelAnts\LaravelTenant\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class TenantManager
{
    private ?Model $tenant;

    public function __construct($tenant = null)
    {
        $this->set($tenant);
    }

    public function set($tenant = null)
    {
        $this->tenant = $tenant;
        if ($tenant != null && config('tenant.resolver') == 'subdomain') {
            $clearTenantRoot = Str::replace(($tenant->slug .'.'), '', trim(trim(trim(config('app.url'), "."), "https://"), "http://"));

            Config::set('app.url_root', config('app.url'));
            Config::set('app.url', (config('app.https') ? 'https://' : 'http://') . $tenant->slug . '.' . $clearTenantRoot);
            if (method_exists(config('tenant.tenant_model'), 'initialize')) {
                tenantManager()->getTenant()->initialize();
            }
        }
    }

    public function getTenant()
    {
        return $this->tenant;
    }
}
