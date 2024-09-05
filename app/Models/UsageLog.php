<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'appliance_id',
        'start_time',
        'end_time',
        'duration',
        'cost',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function appliance()
    {
        return $this->belongsTo(Appliance::class);
    }
}
