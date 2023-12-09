<?php

namespace App\Models;

use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientLink extends Model
{
    protected $table = 'patient_link';
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Valuesets::LinkType
        ]
    ];
}
