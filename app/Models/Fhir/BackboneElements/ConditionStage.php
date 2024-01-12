<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Condition;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ConditionStage extends FhirModel
{
    use HasFactory;

    protected $table = 'condition_stage';

    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    public function summary(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'summary');
    }

    public function assessment(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }

    public function type(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'type');
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
