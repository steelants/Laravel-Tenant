<div align="center">

<a href="https://steelants.cz">
	<picture>
		<source
			media="(prefers-color-scheme: dark)"
			srcset="https://steelants.cz/wp-content/uploads/2026/07/white_3.png">
		<img
			src="https://steelants.cz/wp-content/themes/wp_steelants_v5/img/logo.png"
			alt="SteelAnts"
			width="180">
	</picture>
</a>

<h1>Laravel-Tenant</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/steelants/laravel-tenant.svg?style=flat-square)](https://packagist.org/packages/steelants/laravel-tenant) [![Total Downloads](https://img.shields.io/packagist/dt/steelants/laravel-tenant.svg?style=flat-square)](https://packagist.org/packages/steelants/laravel-tenant)

<p>
Single database multi-tenancy for Laravel with subdomain, path, session and static tenant resolving.
</p>

<p>
Created by <a href="https://steelants.cz">SteelAnts s.r.o.</a>
</p>

</div>

## Features

SteelAnts Laravel-Tenant provides:

- Single database multi-tenancy
- Tenant resolving by subdomain, path, session or static id
- Automatic tenant scoping of Eloquent queries
- Automatic tenant id assignment on created records
- Tenant and user pivot with permissions
- Middleware for tenant protected routes
- `tenant()` and `tenantManager()` helpers

## Documentation

- [Installation](docs/installation.md)
- [Usage](docs/usage.md)
- [Configuration](docs/configuration.md)
- [Resolvers](docs/resolvers.md)
- [Middleware](docs/middleware.md)
- [Development](docs/development.md)

## Contributors

<a href="https://github.com/steelants/Laravel-Tenant/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=steelants/Laravel-Tenant" />
</a>

## Other Packages

- [Laravel-Auth](https://github.com/steelants/Laravel-Auth)
- [Livewire-DataTable](https://github.com/steelants/Livewire-DataTable)
- [Laravel-Boilerplate.Warehouse](https://github.com/steelants/Laravel-Boilerplate.Warehouse)
- [Laravel-Boilerplate](https://github.com/steelants/Laravel-Boilerplate)
- [Laravel-Form](https://github.com/steelants/Laravel-Form)
- [Livewire-Form](https://github.com/steelants/Livewire-Form)
- [Laravel-General](https://github.com/steelants/Laravel-General)
- [Livewire-Modal](https://github.com/steelants/Livewire-Modal)

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
