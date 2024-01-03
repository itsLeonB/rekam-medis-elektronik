<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\FhirModel;
use App\Models\Fhir\Resources\Location;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationHoursOfOperation extends FhirModel
{
    protected $table = 'location_operation_hours';

    protected $casts = [
        'days_of_week' => 'array',
        'all_day' => 'boolean',
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
    ];

    public $timestamps = false;

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public const DAYS_OF_WEEK = [
        'binding' => [
            'valueset' => Valuesets::DaysOfWeek
        ]
    ];
}
