<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientName extends FhirModel
{
    use HasFactory;

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
