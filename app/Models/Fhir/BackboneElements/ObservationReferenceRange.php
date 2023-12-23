<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Range;
use App\Models\Fhir\Datatypes\SimpleQuantity;
use App\Models\Fhir\Resources\Observation;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ObservationReferenceRange extends FhirModel
{
    protected $table = 'observation_ref_range';

    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }

    public function low(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable');
    }

    public function high(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable');
    }

    public function type(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function appliesTo(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
    }

    public function age(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable');
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::ObservationReferenceRangeMeaningCodes
        ]
    ];

    public const APPLIES_TO = [
        'binding' => [
            'valueset' => Valuesets::ObservationReferenceRangeAppliesToCodes
        ]
    ];
}
