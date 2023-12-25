<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\{Codesystems, Valuesets};
use App\Models\FhirModel;
use App\Models\Fhir\Resource;
use App\Models\Fhir\BackboneElements\{
    LocationPosition,
    LocationHoursOfOperation
};
use App\Models\Fhir\Datatypes\{
    Address,
    CodeableConcept,
    Coding,
    ContactPoint,
    Extension,
    Identifier,
    Reference
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
    HasOne,
    MorphMany,
    MorphOne
};

class Location extends FhirModel
{
    protected $table = 'location';

    protected $casts = ['alias' => 'array'];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function operationalStatus(): MorphOne
    {
        return $this->morphOne(Coding::class, 'codeable');
    }

    public function type(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'type');
    }

    public function telecom(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contact_pointable');
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function physicalType(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'physical_type');
    }

    public function position(): HasOne
    {
        return $this->hasOne(LocationPosition::class);
    }

    public function managingOrganization(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'managingOrganization');
    }

    public function partOf(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'partOf');
    }

    public function hoursOfOperation(): HasMany
    {
        return $this->hasMany(LocationHoursOfOperation::class);
    }

    public function endpoint(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'endpoint');
    }

    public function serviceClass(): MorphOne
    {
        return $this->morphOne(Extension::class, 'extendable')->with('valueCodeableConcept');
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
