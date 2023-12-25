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
        'deceased_date_time' => 'datetime',
        'multiple_birth_boolean' => 'boolean',
        'multiple_birth_integer' => 'integer'
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function name(): MorphMany
    {
        return $this->morphMany(HumanName::class, 'human_nameable');
    }

    public function telecom(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contact_pointable');
    }

    public function address(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function maritalStatus(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'marital_status');
    }

    public function photo(): MorphMany
    {
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

    public function generalPractitioner(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'generalPractitioner');
    }

    public function managingOrganization(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'managingOrganization');
    }

    public function link(): HasMany
    {
        return $this->hasMany(PatientLink::class);
    }

    public function birthPlace(): MorphOne
    {
        return $this->morphOne(Extension::class, 'extendable')
            ->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace');
    }

    public function citizenship(): MorphOne
    {
        return $this->morphOne(ComplexExtension::class, 'complex_extendable');
    }

    public function religion(): MorphOne
    {
        return $this->morphOne(Extension::class, 'extendable')
            ->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-religion');
    }

    public function citizenshipStatus(): MorphOne
    {
        return $this->morphOne(Extension::class, 'extendable')
            ->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus');
    }

    public function extendedBirthDate(): MorphOne
    {
        return $this->morphOne(Extension::class, 'extendable')
            ->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-birthTime');
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
