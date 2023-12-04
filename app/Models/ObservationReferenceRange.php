<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationReferenceRange extends Model
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
