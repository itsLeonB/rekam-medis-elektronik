<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use App\Models\PractitionerUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Practitioner extends FhirModel
{
    use HasFactory;

    protected $table = 'practitioner';
    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
        // 'communication' => 'array'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany// HasMany
    {
        // return $this->hasMany(PractitionerIdentifier::class);
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function name(): MorphMany//HasMany
    {
        // return $this->hasMany(PractitionerName::class);
        return $this->morphMany(HumanName::class, 'nameable');
    }

    public function telecom(): MorphMany//HasMany
    {
        // return $this->hasMany(PractitionerTelecom::class);
        return $this->morphMany(ContactPoint::class, 'contact_pointable');
    }

    public function address(): MorphMany//HasMany
    {
        // return $this->hasMany(PractitionerAddress::class);
        return $this->morphMany(Address::class, 'addressable');
    }

    public function photo(): MorphMany// HasMany
    {
        // return $this->hasMany(PractitionerPhoto::class);
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
        return $this->belongsToMany(Practitioner::class);
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
