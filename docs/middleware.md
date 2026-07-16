# Middleware

SteelAnts Laravel-Tenant registers three middleware aliases.

| Alias | Middleware | Description |
|---|---|---|
| `resolve-tenant` | `ResolveTenant` | Resolves the current tenant from the request |
| `has-tenant` | `HasTenant` | Aborts with `404` when no tenant is resolved |
| `dont-have-tenant` | `DontHaveTenant` | For routes outside of a tenant context |


## Tenant Resolving

The `resolve-tenant` middleware is added to the `web` middleware group automatically.

Every web request resolves the tenant using the configured resolver.

For the strategies see:

[Resolvers documentation](resolvers.md)


## Protecting Tenant Routes

Use the `has-tenant` middleware for routes that require a tenant:

```php
Route::middleware(['has-tenant'])->group(function () {
    Route::get('/dashboard', DashboardController::class);
});
```

Requests without a resolved tenant are aborted with a `404` response.


## Routes Outside of a Tenant

Use the `dont-have-tenant` middleware for routes that must not run in a tenant context, for example a central administration:

```php
Route::middleware(['dont-have-tenant'])->group(function () {
    Route::get('/admin', AdminController::class);
});
```


## Next Steps

Continue with:

- [Resolvers](resolvers.md)
- [Usage](usage.md)
- [Configuration](configuration.md)
