<?php

use SteelAnts\LaravelTenant\Models\Tenant;
use SteelAnts\LaravelTenant\Models\TenantUser;

/*
|--------------------------------------------------------------------------
| Tenant Resolver
|--------------------------------------------------------------------------
|
| can be either subdomain ({tenant_slug}.test.test) of path (test.test/{tenant_slug})
|
*/

return [
    'resolver' => 'subdomain',
    'tenant_model' => Tenant::class,
    'tenant_user_model' => TenantUser::class,
];

/*TEST*/