<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Encounter;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EncounterDiagnosis extends FhirModel
{
    use HasFactory;

    protected $table = 'encounter_diagnosis';

    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function condition(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public function use(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::DiagnosisRole
        ]
    ];
}
