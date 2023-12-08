<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerAddress extends Model
{
    protected $table = 'practitioner_address';
    protected $casts = ['line' => 'array'];

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public $timestamps = false;

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

    public const ADMINISTRATIVE_CODE = [
        'binding' => [
            'valueset' => Codesystems::AdministrativeArea
        ]
    ];
}
