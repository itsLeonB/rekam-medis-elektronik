<?php

namespace App\Models\Fhir\BackboneElements;

use App\Models\Fhir\Resources\Medication;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicationBatch extends FhirModel
{
    use HasFactory;

    protected $table = 'medication_batch';

    protected $casts = ['expiration_date' => 'datetime'];

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
