<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientIdentifier extends Model
{
    protected $table = 'patient_identifier';
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::IdentifierUse
        ]
    ];
}
