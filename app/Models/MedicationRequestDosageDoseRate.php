<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationRequestDosageDoseRate extends Model
{
    protected $table = 'med_req_dosage_dose_rate';
    protected $casts = [
        'dose' => 'array',
        'rate' => 'array'
    ];
    public $timestamps = false;

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(MedicationRequestDosage::class, 'med_req_dosage_id');
    }
}
