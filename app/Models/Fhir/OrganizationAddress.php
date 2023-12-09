<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationAddress extends FhirModel
{
    protected $table = 'organization_address';
    protected $casts = ['line' => 'array'];
    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::AddressUse
        ]
    ];

    public const TYPE = [
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
