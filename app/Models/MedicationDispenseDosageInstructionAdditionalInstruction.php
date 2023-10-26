<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class MedicationDispenseDosageInstructionAdditionalInstruction extends Model
{
    protected $table = 'med_disp_dosage_add_instruct';
    public $timestamps = false;

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(MedicationDispenseDosageInstruction::class, 'med_disp_dose_id');
    }
}
