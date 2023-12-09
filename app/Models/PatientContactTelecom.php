<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientContactTelecom extends Model
{
    protected $table = 'patient_contact_telecom';
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(PatientContact::class, 'patient_contact_id');
    }

    public const SYSTEM = [
        'binding' => [
            'valueset' => Codesystems::ContactPointSystem
        ]
    ];

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::ContactPointUse
        ]
    ];
}
