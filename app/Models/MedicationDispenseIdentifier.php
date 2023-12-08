<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispenseIdentifier extends Model
{
    protected $table = 'medication_dispense_identifier';
    public $timestamps = false;

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'dispense_id');
    }
}
