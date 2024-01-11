<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\{Codesystems, Valuesets};
use App\Models\FhirModel;
use App\Models\Fhir\Datatypes\{
    Address,
    CodeableConcept,
    ContactPoint,
    HumanName,
    Period,
    Reference,
};
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    MorphMany,
    MorphOne
};

class PatientContact extends FhirModel
{
    use HasFactory;

    protected $table = 'patient_contact';

    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function relationship(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
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

    public function organization(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public const RELATIONSHIP = [
        'binding' => [
            'valueset' => Valuesets::PatientContactRelationship
        ]
    ];

    public const GENDER = [
        'binding' => [
            'valueset' => Codesystems::AdministrativeGender
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
