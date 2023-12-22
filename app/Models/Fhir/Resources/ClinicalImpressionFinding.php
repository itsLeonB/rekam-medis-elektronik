<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionFinding extends FhirModel
{
    protected $table = 'clinical_impression_finding';
    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }

    public const ITEM_CODEABLE_CONCEPT = [
        'binding' => [
            'valueset' => Codesystems::ICD10
        ],
    ];
}
