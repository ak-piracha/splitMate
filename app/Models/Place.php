<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'principal_tenant_id',
        'name',
        'location',
        'unique_id',
    ];

    public function principalTenant()
    {
        return $this->belongsTo(User::class, 'principal_tenant_id');
    }

    public function tenants()
    {
        return $this->belongsToMany(User::class, 'place_tenant', 'place_id', 'tenant_id');
    }
}
