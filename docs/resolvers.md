# Resolvers

SteelAnts Laravel-Tenant resolves the current tenant on every web request.

The strategy is defined by the `resolver` configuration option:

```php
'resolver' => env('TENANT_RESOLVER', 'subdomain'),
```


## Subdomain

The default resolver.

The tenant slug is taken from the subdomain of the request host:

```
{slug}.example.com
```

The tenant is loaded by the `slug` column.

When a tenant is set, the application URL is rewritten to the tenant subdomain:

- The original URL is preserved in `app.url_root`.
- `app.url` becomes `https://{slug}.example.com`.


## Path

The tenant slug is taken from the `tenant` route parameter:

```
example.com/{tenant}
```

Routes must contain the `{tenant}` parameter.

The tenant is loaded by the `slug` column.


## Session

The tenant id is taken from the `tenant_id` session key:

```php
session()->put('tenant_id', $tenant->id);
```

The tenant is loaded by its primary key.


## Static

The tenant id is fixed by configuration:

```env
TENANT_ID=1
```

The tenant is loaded by its primary key.

This resolver is useful for single tenant deployments of a multi-tenant application.


## Initialize Hook

When the tenant model defines an `initialize()` method, it is called after the tenant is set.

Use the hook for tenant specific setup:

```php
public function initialize(): void
{
    // tenant specific configuration
}
```


## Next Steps

Continue with:

- [Configuration](configuration.md)
- [Middleware](middleware.md)
- [Usage](usage.md)
