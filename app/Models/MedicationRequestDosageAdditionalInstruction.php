<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationRequestDosageAdditionalInstruction extends Model
{
    protected $table = 'med_req_dosage_additional_instruction';
    public $timestamps = false;

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(MedicationRequestDosage::class, 'med_req_dosage_id');
    }
}
