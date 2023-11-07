<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationDispenseDosageInstruction extends Model
{
    protected $table = 'medication_dispense_dosage';
    protected $casts = [
        'timing_event' => 'json',
        'timing_repeat' => 'json',
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

    public function additionalInstruction(): HasMany
    {
        return $this->hasMany(MedicationDispenseDosageInstructionAdditionalInstruction::class, 'med_disp_dose_id');
    }

    public function doseRate(): HasMany
    {
        return $this->hasMany(MedicationDispenseDosageInstructionDoseRate::class, 'med_disp_dose_id');
    }
}
