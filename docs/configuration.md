# Configuration

SteelAnts Laravel-Tenant is configured using the `config/tenant.php` file.

Publish the configuration:

```bash
php artisan vendor:publish --tag=tenant-config
```


## Options

```php
return [
    'resolver' => env('TENANT_RESOLVER', 'subdomain'),
    'tenant_model' => Tenant::class,
    'tenant_user_model' => TenantUser::class,
    'tenant_id' => env('TENANT_ID', 0),
];
```

| Option | Default | Description |
|---|---|---|
| `resolver` | `subdomain` | Tenant resolving strategy (`subdomain`, `path`, `session`, `static`) |
| `tenant_model` | `Tenant::class` | Tenant model class |
| `tenant_user_model` | `TenantUser::class` | Tenant user pivot class |
| `tenant_id` | `0` | Fixed tenant id (only for the `static` resolver) |


## Environment Variables

```env
TENANT_RESOLVER=subdomain
TENANT_ID=0
```


## Custom Models

You can replace the built-in models with your own classes:

```php
'tenant_model' => App\Models\Tenant::class,
'tenant_user_model' => App\Models\TenantUser::class,
```

When the tenant model defines an `initialize()` method, it is called after the tenant is set.


## Next Steps

Continue with:

- [Usage](usage.md)
- [Resolvers](resolvers.md)
- [Middleware](middleware.md)
