<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\ClinicalImpression;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ClinicalImpressionFinding extends FhirModel
{
    protected $table = 'clinical_impression_finding';
    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }

    public function itemCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function itemReference(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public const ITEM_CODEABLE_CONCEPT = [
        'binding' => [
            'valueset' => Codesystems::ICD10
        ],
    ];
}
