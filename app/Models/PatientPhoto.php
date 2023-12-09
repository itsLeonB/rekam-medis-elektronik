<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientPhoto extends Model
{
    protected $table = 'patient_photo';
    protected $casts = ['creation' => 'datetime'];
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
