<?php

namespace App\Models;

use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispenseDosageInstructionDoseRate extends Model
{
    protected $table = 'med_disp_dosage_dose_rate';
    protected $casts = [
        'dose' => 'array',
        'rate' => 'array'
    ];
    public $timestamps = false;

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(MedicationDispenseDosageInstruction::class, 'med_disp_dose_id');
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Valuesets::DoseAndRateType
        ]
    ];

    public const DOSE = [
        'binding' => [
            'valueset' => MedicationIngredient::STRENGTH_DENOMINATOR['binding']['valueset']
        ]
    ];

    public const RATE = [
        'binding' => [
            'valueset' => MedicationIngredient::STRENGTH_DENOMINATOR['binding']['valueset']
        ]
    ];
}
