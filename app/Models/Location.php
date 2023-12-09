<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($location) {
    //         $orgId = config('app.organization_id');

    //         $identifier = new LocationIdentifier();
    //         $identifier->system = 'http://sys-ids.kemkes.go.id/location/' . $orgId;
    //         $identifier->use = 'official';
    //         $identifier->value = $location->identifier()->max('value') + 1;

    //         // Save the identifier through the relationship
    //         $location->identifier()->save($identifier);
    //     });
    // }

    protected $table = 'location';
    protected $casts = [
        'alias' => 'array',
        'type' => 'array',
        'address_line' => 'array',
        'longitude' => 'double',
        'latitude' => 'double',
        'altitude' => 'double',
        'endpoint' => 'array'
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

    public function telecom(): HasMany
    {
        return $this->hasMany(LocationTelecom::class);
    }

    public function operationHours(): HasMany
    {
        return $this->hasMany(LocationOperationHours::class);
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::LocationStatus
        ]
    ];

    public const OPERATIONAL_STATUS = [
        'binding' => [
            'valueset' => Codesystems::v20116
        ]
    ];

    public const MODE = [
        'binding' => [
            'valueset' => Codesystems::LocationMode
        ]
    ];

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::LocationType
        ]
    ];

    public const PHYSICAL_TYPE = [
        'binding' => [
            'valueset' => Valuesets::LocationPhysicalType
        ]
    ];

    public const SERVICE_CLASS = [
        'binding' => [
            'valueset' => Codesystems::LocationServiceClass
        ]
    ];

    public const ADDRESS_USE = [
        'binding' => [
            'valueset' => Codesystems::AddressUse
        ]
    ];

    public const ADDRESS_TYPE = [
        'binding' => [
            'valueset' => Codesystems::AddressType
        ]
    ];

    public const COUNTRY = [
        'binding' => [
            'valueset' => Codesystems::ISO3166
        ]
    ];

    public const ADMINISTRATIVE_CODE = [
        'url' => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
        'binding' => [
            'valueset' => Codesystems::AdministrativeArea
        ]
    ];
}
