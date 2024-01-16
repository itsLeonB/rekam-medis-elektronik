<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use App\Models\Fhir\Resource;
use App\Models\Fhir\BackboneElements\PractitionerQualification;
use App\Models\Fhir\Datatypes\{
    Address,
    Attachment,
    CodeableConcept,
    ContactPoint,
    HumanName,
    Identifier,
};
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
    HasMany,
    MorphMany,
};

class Practitioner extends FhirModel
{
    use HasFactory;

    protected $table = 'practitioner';

    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'resource_id');
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

    public function photo(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function qualification(): HasMany
    {
        return $this->hasMany(PractitionerQualification::class);
    }

    public function communication(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
    }

    public function practitionerUser(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public const GENDER = [
        'binding' => [
            'valueset' => Codesystems::AdministrativeGender
        ]
    ];

    public const COMMUNICATION = [
        'binding' => [
            'valueset' => Codesystems::BCP47
        ]
    ];
}
