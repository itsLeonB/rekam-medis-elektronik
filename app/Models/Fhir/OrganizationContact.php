<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationContact extends FhirModel
{
    protected $table = 'organization_contact';
    protected $casts = [
        'name_given' => 'array',
        'name_prefix' => 'array',
        'name_suffix' => 'array',
        'address_line' => 'array'
    ];
    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(OrganizationContactTelecom::class, 'organization_contact_id', 'id');
    }

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
