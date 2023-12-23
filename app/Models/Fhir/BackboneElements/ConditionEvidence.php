<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Condition;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ConditionEvidence extends FhirModel
{
    protected $table = 'condition_evidence';

    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    public function code(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
    }

    public function detail(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::ManifestationAndSymptomCodes
        ]
    ];
}
