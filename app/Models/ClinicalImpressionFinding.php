<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionFinding extends Model
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
