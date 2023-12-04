<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObservationComponent extends Model
{
    protected $table = 'observation_component';
    protected $casts = [
        'value' => 'array',
        'interpretation' => 'array'
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
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
