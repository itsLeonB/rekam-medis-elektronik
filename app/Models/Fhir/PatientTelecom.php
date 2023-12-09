<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientTelecom extends FhirModel
{
    protected $table = 'patient_telecom';
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
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
