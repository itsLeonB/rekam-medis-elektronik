<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $table = 'location';

    protected $casts = [
        'active' => 'boolean',
        'longitude' => 'double',
        'latitude' => 'double',
        'altitude' => 'double'
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo('resource', 'res_id', 'res_id');
    }

    public function identifier(): HasMany
    {
        return $this->hasMany('location_identifier', 'location_id', 'id');
    }

    public function type(): HasMany
    {
        return $this->hasMany('location_type', 'location_id', 'id');
    }

    public function telecom(): HasMany
    {
        return $this->hasMany('location_telecom', 'location_id', 'id');
    }

    public function operationHours(): HasMany
    {
        return $this->hasMany('location_operation_hours', 'location_id', 'id');
    }
}
