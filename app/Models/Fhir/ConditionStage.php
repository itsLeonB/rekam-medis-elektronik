<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionStage extends FhirModel
{
    protected $table = 'condition_stage';
    protected $casts = ['assessment' => 'array'];
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    public const SUMMARY = [
        'binding' => [
            'valueset' => Valuesets::ConditionStage
        ]
    ];

    public const TYPE = [
        'binding' => [
            'valueset' => Valuesets::ConditionStageType
        ]
    ];
}
