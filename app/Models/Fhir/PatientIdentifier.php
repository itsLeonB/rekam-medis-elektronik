<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientIdentifier extends FhirModel
{
    use HasFactory;

    protected $table = 'patient_identifier';
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::IdentifierUse
        ]
    ];
}
