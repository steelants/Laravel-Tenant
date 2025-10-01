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
            if (tenant() == null && tenantManager() == null) {
                return $model;
            }

            if (empty($model->tenant_id)) {
                $model->tenant_id = (tenant()->id ?? tenantManager()->id);
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
