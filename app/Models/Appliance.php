<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appliance extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'name',
        'cost_per_hour',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function usageLogs()
    {
        return $this->hasMany(UsageLog::class);
    }
}
