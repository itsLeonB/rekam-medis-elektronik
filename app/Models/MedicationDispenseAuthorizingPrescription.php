<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispenseAuthorizingPrescription extends Model
{
    protected $table = 'medication_dispense_authorizing_prescription';
    public $timestamps = false;

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'dispense_id');
    }
}
