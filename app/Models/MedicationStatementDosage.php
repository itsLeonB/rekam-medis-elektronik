<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationStatementDosage extends Model
{
    protected $table = 'medication_statement_dosage';
    protected $casts = [
        'additional_instruction' => 'array',
        'timing_event' => 'array',
        'timing_repeat' => 'array',
        'as_needed' => 'array',
        'max_dose_per_period_numerator_value' => 'decimal:2',
        'max_dose_per_period_denominator_value' => 'decimal:2',
        'max_dose_per_administration_value' => 'decimal:2',
        'max_dose_per_lifetime_value' => 'decimal:2',
    ];
    public $timestamps = false;

    public function medicationStatement(): BelongsTo
    {
        return $this->belongsTo(MedicationStatement::class, 'statement_id');
    }

    public function doseRate(): HasMany
    {
        return $this->hasMany(MedicationStatementDosageDoseRate::class, 'med_state_dose_id');
    }
}
