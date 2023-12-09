<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationOperationHours extends Model
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
