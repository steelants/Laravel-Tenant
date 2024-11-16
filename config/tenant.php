<?php

use SteelAnts\LaravelTenant\Models\Tenant;
use SteelAnts\LaravelTenant\Models\TenantUser;

/*
|--------------------------------------------------------------------------
| Tenant Resolver
|--------------------------------------------------------------------------
|
| can be either subdomain ({tenant_slug}.test.test) or path (test.test/{tenant_slug}) or session or static (tenant id is set in env)
|
*/

return [
    'resolver' => env('TENANT_RESOLVER', 'subdomain'),
    'tenant_model' => Tenant::class,
    'tenant_user_model' => TenantUser::class,
    'tenant_id' => env('TENANT_ID', 0), // only for "static" resolver
];

/*TEST*/