<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientName extends Model
{
    protected $table = 'patient_name';
    protected $casts = [
        'given' => 'array',
        'prefix' => 'array',
        'suffix' => 'array',
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::NameUse
        ]
    ];
}
