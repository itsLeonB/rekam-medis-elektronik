<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Practitioner extends FhirModel
{
    protected $table = 'practitioner';
    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
        'communication' => 'array'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(PractitionerIdentifier::class);
    }

    public function name(): HasMany
    {
        return $this->hasMany(PractitionerName::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(PractitionerTelecom::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(PractitionerAddress::class);
    }

    public function photo(): HasMany
    {
        return $this->hasMany(PractitionerPhoto::class);
    }

    public function qualification(): HasMany
    {
        return $this->hasMany(PractitionerQualification::class);
    }

    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'practitioner_id', 'id');
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
