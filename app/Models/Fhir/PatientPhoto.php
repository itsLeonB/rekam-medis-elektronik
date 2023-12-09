<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientPhoto extends FhirModel
{
    protected $table = 'patient_photo';
    protected $casts = ['creation' => 'datetime'];
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
