<?php

namespace App\Models;

use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionInvestigation extends Model
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
