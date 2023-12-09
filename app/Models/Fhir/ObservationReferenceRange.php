<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationReferenceRange extends FhirModel
{
    protected $table = 'observation_ref_range';
    protected $casts = [
        'low_value' => 'decimal:2',
        'high_value' => 'decimal:2',
        'applies_to' => 'array'
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
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
