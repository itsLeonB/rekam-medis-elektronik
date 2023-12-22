<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispensePerformer extends FhirModel
{
    protected $table = 'medication_dispense_performer';
    public $timestamps = false;

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'dispense_id');
    }

    public const FUNCTION = [
        'binding' => [
            'valueset' => Codesystems::MedicationDispensePerformerFunctionCodes
        ]
    ];
}
