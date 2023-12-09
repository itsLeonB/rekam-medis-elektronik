<?php

namespace App\Models\Fhir;


use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationRequestIdentifier extends FhirModel
{
    protected $table = 'medication_request_identifier';
    public $timestamps = false;

    public function medicationRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'med_req_id');
    }
}
