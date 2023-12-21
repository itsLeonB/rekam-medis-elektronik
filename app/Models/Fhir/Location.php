<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Location extends FhirModel
{
    protected $table = 'location';
    protected $casts = [
        'alias' => 'array',
        // 'type' => 'array',
        // 'address_line' => 'array',
        // 'longitude' => 'double',
        // 'latitude' => 'double',
        // 'altitude' => 'double',
        // 'endpoint' => 'array'
    ];
    public $timestamps = false;

    protected $with = ['identifier', 'operationalStatus', 'type', 'telecom', 'address', 'physicalType', 'position', 'managingOrganization', 'partOf', 'hoursOfOperation', 'endpoint'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany//HasMany
    {
        // return $this->hasMany(LocationIdentifier::class);
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function operationalStatus(): MorphOne
    {
        return $this->morphOne(Coding::class, 'codeable');
    }

    public function codeableConcepts(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
    }

    public function type(): MorphMany
    {
        return $this->codeableConcepts()->where('attr_type', 'type');
    }

    public function telecom(): MorphMany//HasMany
    {
        // return $this->hasMany(LocationTelecom::class);
        return $this->morphMany(ContactPoint::class, 'contact_pointable');
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function physicalType(): MorphOne
    {
        return $this->codeableConcepts()->where('attr_type', 'physical_type');
    }

    public function position(): HasOne
    {
        return $this->hasOne(LocationPosition::class);
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }

    public function managingOrganization(): MorphOne
    {
        return $this->references()->where('attr_type', 'managingOrganization');
    }

    public function partOf(): MorphOne
    {
        return $this->references()->where('attr_type', 'partOf');
    }

    public function hoursOfOperation(): HasMany
    {
        return $this->hasMany(LocationHoursOfOperation::class);
    }

    public function endpoint(): MorphMany
    {
        return $this->references()->where('attr_type', 'endpoint');
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
