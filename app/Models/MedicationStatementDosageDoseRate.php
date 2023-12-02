<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationStatementDosageDoseRate extends Model
{
    protected $table = 'med_state_dosage_dose_rate';
    protected $casts = [
        'dose' => 'array',
        'rate' => 'array',
    ];
    public $timestamps = false;

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(MedicationStatementDosage::class, 'med_state_dose_id');
    }
}
