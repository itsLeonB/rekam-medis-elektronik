<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationDispenseDosageInstruction extends Model
{
    protected $table = 'medication_dispense_dosage';
    protected $casts = [
        'timing_event' => 'json',
        'timing_repeat' => 'json',
        'max_dose_per_period_numerator_value' => 'decimal',
        'max_dose_per_period_denominator_value' => 'decimal',
        'max_dose_per_administration_value' => 'decimal',
        'max_dose_per_lifetime_value' => 'decimal'
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
