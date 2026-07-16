# Installation

SteelAnts Laravel-Tenant is installed using Composer.


## Requirements

- Laravel 11 or 12


## Install the Package

Install the package using Composer:

```bash
composer require steelants/laravel-tenant
```

Laravel automatically discovers the service provider.


## Run the Migrations

Publish the package migrations:

```bash
php artisan vendor:publish --tag=tenant-migrations
```

Run the migrations:

```bash
php artisan migrate
```

The migrations:

1. Create the `tenants` table.
2. Add a nullable `tenant_id` foreign key to all existing tables.
3. Create the `tenant_user` pivot table.
4. Add indexes to the `tenant_id` columns.

System tables are skipped automatically:

`jobs`, `job_batches`, `failed_jobs`, `users`, `migrations`, `password_resets`, `password_reset_tokens`, `tenants`, `cache`, `cache_locks`, `sessions`

> Run the migrations after your application tables exist.
> Tables created later need their own `tenant_id` column.


## Publish the Configuration

Optionally publish the configuration file:

```bash
php artisan vendor:publish --tag=tenant-config
```

For all options see:

[Configuration documentation](configuration.md)


## Next Steps

Continue with:

- [Usage](usage.md)
- [Configuration](configuration.md)
- [Resolvers](resolvers.md)
