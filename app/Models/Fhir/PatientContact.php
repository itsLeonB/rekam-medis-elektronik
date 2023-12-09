<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientContact extends FhirModel
{
    protected $table = 'patient_contact';
    protected $casts = [
        'relationship' => 'array',
        'name_given' => 'array',
        'name_prefix' => 'array',
        'name_suffix' => 'array',
        'address_line' => 'array',
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(PatientContactTelecom::class, 'contact_id');
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
