<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $table = 'patient';
    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
        'deceased' => 'array',
        'multiple_birth' => 'array',
        'general_practitioner' => 'array'
    ];
    protected $with = ['identifier', 'name', 'telecom', 'address', 'photo', 'contact', 'communication', 'link'];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(PatientIdentifier::class);
    }

    public function name(): HasMany
    {
        return $this->hasMany(PatientName::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(PatientTelecom::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(PatientAddress::class);
    }

    public function photo(): HasMany
    {
        return $this->hasMany(PatientPhoto::class);
    }

    public function contact(): HasMany
    {
        return $this->hasMany(PatientContact::class);
    }

    public function communication(): HasMany
    {
        return $this->hasMany(PatientCommunication::class);
    }

    public function link(): HasMany
    {
        return $this->hasMany(PatientLink::class);
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
