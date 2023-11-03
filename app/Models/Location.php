<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
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
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(LocationIdentifier::class);
    }

    public function type(): HasMany
    {
        return $this->hasMany(LocationType::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(LocationTelecom::class);
    }

    public function operationHours(): HasMany
    {
        return $this->hasMany(LocationOperationHours::class);
    }
}
