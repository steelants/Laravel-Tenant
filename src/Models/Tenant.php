<?php

namespace SteelAnts\LaravelTenant\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = strtolower($value);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->using(TenantUser::class)->withPivot(["permission"]);
    }
}
