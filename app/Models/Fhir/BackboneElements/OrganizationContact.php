<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use App\Models\Fhir\Datatypes\{
    Address,
    CodeableConcept,
    ContactPoint,
    HumanName,
};
use App\Models\Fhir\Resources\Organization;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    MorphMany,
    MorphOne
};

class OrganizationContact extends FhirModel
{
    protected $table = 'organization_contact';

    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function purpose(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function name(): MorphOne
    {
        return $this->morphOne(HumanName::class, 'human_nameable');
    }

    public function telecom(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contact_pointable');
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    // public function telecom(): HasMany
    // {
    //     return $this->hasMany(OrganizationContactTelecom::class, 'organization_contact_id', 'id');
    // }

    public const PURPOSE = [
        'binding' => [
            'valueset' => Codesystems::ContactEntityType
        ]
    ];

    public const NAME_USE = [
        'binding' => [
            'valueset' => Codesystems::NameUse
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
        'binding' => [
            'valueset' => Codesystems::AdministrativeArea
        ]
    ];
}
