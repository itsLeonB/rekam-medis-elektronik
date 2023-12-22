<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionEvidence extends FhirModel
{
    protected $table = 'condition_evidence';
    protected $casts = [
        'code' => 'array',
        'detail' => 'array'
    ];
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::ManifestationAndSymptomCodes
        ]
    ];
}
