<?php

namespace SteelAnts\LaravelTenant\Traits;

use Jinas\LaravelTenant\Models\Tenant;


trait HasTenant
{
    protected static function bootHasTenant()
    {
        static::addGlobalScope(new Tenantsc);

        static::creating(function ($model) {
            if (session()->has('tenant_id')) {
                $model->tenant_id = session()->get('tenant_id');
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}