<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    MorphOne
};

class PatientCommunication extends FhirModel
{
    protected $table = 'patient_communication';
    protected $casts = ['preferred' => 'boolean'];
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function language(): MorphOne //BelongsTo
    {
        // return $this->belongsTo(CodeableConcept::class, 'language_id');
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public const LANGUAGE = [
        'binding' => [
            'valueset' => Codesystems::BCP47
        ]
    ];
}
