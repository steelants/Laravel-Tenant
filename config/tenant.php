<?php

use SteelAnts\LaravelTenant\Models\Tenant;
use SteelAnts\LaravelTenant\Models\TenantUser;

/*
|--------------------------------------------------------------------------
| Tenant Resolver
|--------------------------------------------------------------------------
|
| can be either subdomain ({tenant_slug}.test.test) or path (test.test/{tenant_slug}) or session
|
*/

return [
    'resolver' => 'subdomain',
    'tenant_model' => Tenant::class,
    'tenant_user_model' => TenantUser::class,
];

/*TEST*/