<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionNote extends FhirModel
{
    protected $table = 'clinical_impression_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }
}
