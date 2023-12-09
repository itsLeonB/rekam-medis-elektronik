<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerTelecom extends FhirModel
{
    protected $table = 'practitioner_telecom';
    public $timestamps = false;

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public const SYSTEM = [
        'binding' => [
            'valueset' => Codesystems::ContactPointSystem
        ]
    ];

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::ContactPointUse
        ]
    ];
}
