<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionInvestigation extends FhirModel
{
    protected $table = 'clinical_impression_investigation';
    protected $casts = ['item' => 'array'];
    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::InvestigationType
        ],
    ];
}
