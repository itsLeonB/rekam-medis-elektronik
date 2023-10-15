<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientPhoto extends Model
{
    use HasFactory;

    protected $table = 'patient_photo';

    protected $casts = [
        'creation' => 'datetime'
    ];

    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'id', 'patient_id');
    }
}