<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationRequestNote extends FhirModel
{
    protected $table = 'medication_request_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function medicationRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'med_req_id');
    }
}
