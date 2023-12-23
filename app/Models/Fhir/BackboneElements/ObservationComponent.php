<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Quantity;
use App\Models\Fhir\Datatypes\Range;
use App\Models\Fhir\Datatypes\Ratio;
use App\Models\Fhir\Datatypes\SampledData;
use App\Models\Fhir\Resources\Observation;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ObservationComponent extends FhirModel
{
    protected $table = 'observation_component';

    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'code');
    }

    public function valueQuantity(): MorphOne
    {
        return $this->morphOne(Quantity::class, 'value_quantifiable');
    }

    public function valueCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'valueCodeableConcept');
    }

    public function valueRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable');
    }

    public function valueRatio(): MorphOne
    {
        return $this->morphOne(Ratio::class, 'rateable');
    }

    public function valueSampledData(): MorphOne
    {
        return $this->morphOne(SampledData::class, 'sampled_dataable');
    }

    public function valuePeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function dataAbsentReason(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'dataAbsentReason');
    }

    public function interpretation(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'interpretation');
    }

    public function referenceRange(): HasMany
    {
        return $this->hasMany(ObservationComponentReferenceRange::class, 'obs_comp_id');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Codesystems::LOINC
        ]
    ];

    public const VALUE = [
        'variableTypes' => ['valueQuantity', 'valueCodeableConcept', 'valueString', 'valueBoolean', 'valueInteger', 'valueRange', 'valueRatio', 'valueSampledData', 'valueTime', 'valueDateTime', 'valuePeriod']
    ];

    public const VALUE_QUANTITY = [
        'binding' => [
            'valueset' => Codesystems::UCUM
        ]
    ];

    public const VALUE_CODEABLE_CONCEPT = [
        'binding' => [
            'valueset' => [Codesystems::SNOMEDCT, Codesystems::LOINC]
        ]
    ];

    public const DATA_ABSENT_REASON = [
        'binding' => [
            'valueset' => Codesystems::DataAbsentReason
        ]
    ];

    public const INTERPRETATION = [
        'binding' => [
            'valueset' => Valuesets::ObservationInterpretationCodes
        ]
    ];
}
