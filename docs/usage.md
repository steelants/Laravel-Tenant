# Usage

SteelAnts Laravel-Tenant separates data of multiple tenants inside a single database.

The current tenant is resolved automatically on every web request.

For the resolving strategies see:

[Resolvers documentation](resolvers.md)


## Helpers

Access the current tenant anywhere in the application:

```php
// current tenant model
tenant();

// tenant manager instance
tenantManager();
```

`tenant()` is a shortcut for:

```php
app(TenantManager::class)->getTenant();
```


## Tenant Models

Add the `HasTenant` trait to every model that belongs to a tenant:

```php
use SteelAnts\LaravelTenant\Traits\HasTenant;

class Project extends Model
{
    use HasTenant;
}
```

The trait:

1. Applies a global scope - queries return only records of the current tenant.
2. Assigns `tenant_id` automatically when a record is created.
3. Adds a `tenant()` relationship.


## The Tenant Model

The `Tenant` model contains:

| Attribute | Description |
|---|---|
| `name` | Tenant name |
| `slug` | Tenant slug used for resolving; stored lowercase |

Users are attached using a many-to-many relationship with a `permission` pivot column:

```php
$tenant->users;

$tenant->users()->attach($user->id, ['permission' => 'admin']);
```


## Console and Jobs

On the web the tenant is resolved automatically.

In console commands, jobs or scheduled tasks you must set the tenant manually:

```php
use SteelAnts\LaravelTenant\Models\Tenant;

$tenant = Tenant::find($tenantId);

tenantManager()->set($tenant);
```

All tenant scoped queries after the call use the given tenant.


## Next Steps

Continue with:

- [Configuration](configuration.md)
- [Resolvers](resolvers.md)
- [Middleware](middleware.md)
