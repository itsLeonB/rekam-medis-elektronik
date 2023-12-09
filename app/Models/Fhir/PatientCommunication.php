<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientCommunication extends FhirModel
{
    protected $table = 'patient_communication';
    protected $casts = ['preferred' => 'boolean'];
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public const LANGUAGE = [
        'binding' => [
            'valueset' => Codesystems::BCP47
        ]
    ];
}
