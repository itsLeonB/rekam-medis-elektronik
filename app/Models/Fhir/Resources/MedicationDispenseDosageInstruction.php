<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationDispenseDosageInstruction extends FhirModel
{
    protected $table = 'medication_dispense_dosage';
    protected $casts = [
        'additional_instruction' => 'array',
        'timing_event' => 'array',
        'timing_repeat' => 'array',
        'max_dose_per_period_numerator_value' => 'decimal:2',
        'max_dose_per_period_denominator_value' => 'decimal:2',
        'max_dose_per_administration_value' => 'decimal:2',
        'max_dose_per_lifetime_value' => 'decimal:2'
    ];
    public $timestamps = false;

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'dispense_id');
    }

    public function doseRate(): HasMany
    {
        return $this->hasMany(MedicationDispenseDosageInstructionDoseRate::class, 'med_disp_dose_id');
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
