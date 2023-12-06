<?php

namespace App\Models;

use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationRequestDosage extends Model
{
    protected $table = 'medication_request_dosage';
    protected $casts = [
        'additional_instruction' => 'array',
        'timing_event' => 'array',
        'timing_repeat' => 'array',
        'as_needed' => 'array',
        'max_dose_per_period_numerator_value' => 'decimal:2',
        'max_dose_per_period_denominator_value' => 'decimal:2',
        'max_dose_per_administration_value' => 'decimal:2',
        'max_dose_per_lifetime_value' => 'decimal:2'
    ];
    public $timestamps = false;

    public function medicationRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'med_req_id');
    }

    public function doseRate(): HasMany
    {
        return $this->hasMany(MedicationRequestDosageDoseRate::class, 'med_req_dosage_id');
    }

    public const ADDITIONAL_INSTRUCTION = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTAdditionalDosageInstructions
        ]
    ];

    public const TIMING_REPEAT_DURATION_UNIT = [
        'binding' => [
            'valueset' => Valuesets::UnitsOfTime
        ]
    ];

    public const TIMING_REPEAT_PERIOD_UNIT = [
        'binding' => [
            'valueset' => Valuesets::UnitsOfTime
        ]
    ];

    public const TIMING_REPEAT_DAY_OF_WEEK = [
        'binding' => [
            'valueset' => Valuesets::DaysOfWeek
        ]
    ];

    public const TIMING_REPEAT_WHEN = [
        'binding' => [
            'valueset' => Valuesets::EventTiming
        ]
    ];

    public const TIMING_CODE = [
        'binding' => [
            'valueset' => Valuesets::TimingAbbreviation
        ]
    ];

    public const SITE = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTAnatomicalStructureForAdministrationSiteCodes
        ]
    ];

    public const ROUTE = [
        'binding' => [
            'valueset' => Valuesets::DosageRoute
        ]
    ];

    public const METHOD = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTAdministrationMethodCodes
        ]
    ];
}
