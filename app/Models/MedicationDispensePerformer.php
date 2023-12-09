<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispensePerformer extends Model
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
