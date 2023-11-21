<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($location) {
            $orgId = config('app.organization_id');

            $identifier = new LocationIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/location/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $location->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $location->identifier()->save($identifier);
        });
    }

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
