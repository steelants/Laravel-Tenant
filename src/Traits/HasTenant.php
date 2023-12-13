<?php

namespace SteelAnts\LaravelTenant\Traits;

use SteelAnts\LaravelTenant\Models\Tenant;
use SteelAnts\LaravelTenant\Scopes\TenantScope;

trait HasTenant
{
    protected static function bootHasTenant()
    {
        static::addGlobalScope(new TenantScope());
        static::creating(function ($model) {
            if (app()->runningInConsole()) {
                return $model;
            }

            if (!session()->has('tenant_id')) {
                return $model;
            }

            $model->tenant_id = session()->get('tenant_id');
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
