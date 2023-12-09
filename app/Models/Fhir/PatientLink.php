<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientLink extends FhirModel
{
    protected $table = 'patient_link';
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Valuesets::LinkType
        ]
    ];
}
