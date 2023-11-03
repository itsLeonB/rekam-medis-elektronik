<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispenseDosageInstructionDoseRate extends Model
{
    protected $table = 'med_disp_dosage_dose_rate';
    protected $casts = [
        'dose' => 'json',
        'rate' => 'json'
    ];
    public $timestamps = false;

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(MedicationDispenseDosageInstruction::class, 'med_disp_dose_id');
    }
}
