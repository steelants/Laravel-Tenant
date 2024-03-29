<?php

namespace SteelAnts\LaravelTenant\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TenantUser extends Pivot
{
    protected $fillable = [
        'user_id',
        'tenant_id',
        'permission',
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function tenant()
    {
        return $this->belongsTo('Tenant');
    }
}
