# Development

This guide describes how to develop SteelAnts Laravel-Tenant locally inside a Laravel application.


## Local Setup

Create a packages directory and clone the repository:

```bash
mkdir packages
git clone https://github.com/steelants/Laravel-Tenant.git ./packages/Laravel-Tenant
```

Update the autoload section of your application `composer.json`:

```json
"autoload": {
    "psr-4": {
        "SteelAnts\\LaravelTenant\\": "packages/Laravel-Tenant/src/"
    },
    "files": [
        "packages/Laravel-Tenant/src/helpers.php"
    ]
}
```

Refresh the autoloader:

```bash
composer dump-autoload
```

Register the service provider in `bootstrap/providers.php`:

```php
return [
    // ...
    SteelAnts\LaravelTenant\TenantServiceProvider::class,
];
```


## Development Workflow

1. Create a feature branch.
2. Implement changes.
3. Verify the behavior in a test application.
4. Merge changes into the development branch.


## Code Style

The package uses PHP_CodeSniffer with the Slevomat coding standard.

Check the code style:

```bash
composer lint
```

Fix the code style automatically:

```bash
composer format
```

Run static analysis:

```bash
composer check-static
```


## Next Steps

Continue with:

- [Usage](usage.md)
- [Configuration](configuration.md)
