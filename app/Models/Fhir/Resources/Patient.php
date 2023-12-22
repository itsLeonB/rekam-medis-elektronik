<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\{Codesystems, Valuesets};
use App\Models\FhirModel;
use App\Models\Fhir\Resource;
use App\Models\Fhir\BackboneElements\{
    PatientCommunication,
    PatientContact,
    PatientLink,
};
use App\Models\Fhir\Datatypes\{
    Address,
    Attachment,
    CodeableConcept,
    ComplexExtension,
    ContactPoint,
    Extension,
    HumanName,
    Identifier,
    Reference,
};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
    MorphMany,
    MorphOne,
};

class Patient extends FhirModel
{
    use HasFactory;

    protected $table = 'patient';
    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
        'deceased_boolean' => 'boolean',
        'deceased_datetime' => 'datetime',
        'multiple_birth_boolean' => 'boolean',
        'multiple_birth_integer' => 'integer',
        // 'general_practitioner' => 'array'
    ];
    protected $with = ['identifier', 'name', 'telecom', 'address', 'photo', 'contact', 'communication', 'link'];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany //HasMany
    {
        // return $this->hasMany(PatientIdentifier::class);
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function name(): MorphMany //HasMany
    {
        // return $this->hasMany(PatientName::class);
        return $this->morphMany(HumanName::class, 'human_nameable');
    }

    public function telecom(): MorphMany //HasMany
    {
        // return $this->hasMany(PatientTelecom::class);
        return $this->morphMany(ContactPoint::class, 'contact_pointable');
    }

    public function address(): MorphMany //HasMany
    {
        // return $this->hasMany(PatientAddress::class);
        return $this->morphMany(Address::class, 'addressable');
    }

    public function codeableConcepts(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
    }

    public function maritalStatus()
    {
        return $this->codeableConcepts()->where('attr_type', 'marital_status');
    }

    public function photo(): MorphMany //HasMany
    {
        // return $this->hasMany(PatientPhoto::class);
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function contact(): HasMany
    {
        return $this->hasMany(PatientContact::class);
    }

    public function communication(): HasMany
    {
        return $this->hasMany(PatientCommunication::class);
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }

    public function generalPractitioner()
    {
        return $this->references()->where('attr_type', 'generalPractitioner');
    }

    public function managingOrganization()
    {
        return $this->references()->where('attr_type', 'managingOrganization');
    }

    public function link(): HasMany
    {
        return $this->hasMany(PatientLink::class);
    }

    public function extensions(): MorphMany
    {
        return $this->morphMany(Extension::class, 'extendable');
    }

    public function birthPlace()
    {
        return $this->extensions()->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace');
    }

    public function citizenship(): MorphOne
    {
        return $this->morphOne(ComplexExtension::class, 'extendable');
    }

    public function religion()
    {
        return $this->extensions()->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-religion');
    }

    public function citizenshipStatus()
    {
        return $this->extensions()->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus');
    }

    public const GENDER = [
        'binding' => [
            'valueset' => Codesystems::AdministrativeGender
        ]
    ];

    public const DECEASED = [
        'variableTypes' => ['deceasedBoolean', 'deceasedDateTime']
    ];

    public const MARITAL_STATUS = [
        'binding' => [
            'valueset' => Valuesets::MaritalStatusCodes
        ]
    ];

    public const MULTIPLE_BIRTH = [
        'variableTypes' => ['multipleBirthBoolean', 'multipleBirthInteger']
    ];

    public const BIRTH_COUNTRY = [
        'binding' => [
            'valueset' => Codesystems::ISO3166
        ]
    ];
}
