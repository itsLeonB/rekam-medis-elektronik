<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationRequestDosage extends Model
{
    protected $table = 'medication_request_dosage';
    protected $casts = [
        'timing_event' => 'json',
        'timing_repeat' => 'json',
        'max_dose_per_period_numerator_value' => 'decimal',
        'max_dose_per_period_denominator_value' => 'decimal',
        'max_dose_per_administration_value' => 'decimal',
        'max_dose_per_lifetime_value' => 'decimal'
    ];
    public $timestamps = false;

    public function medicationRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'med_req_id');
    }

    public function additionalInstruction(): HasMany
    {
        return $this->hasMany(MedicationRequestDosageAdditionalInstruction::class, 'med_req_dosage_id');
    }

    public function doseRate(): HasMany
    {
        return $this->hasMany(MedicationRequestDosageDoseRate::class, 'med_req_dosage_id');
    }
}
