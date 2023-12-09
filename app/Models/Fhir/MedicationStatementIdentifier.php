<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationStatementIdentifier extends FhirModel
{
    protected $table = 'medication_statement_identifier';
    public $timestamps = false;

    public function medicationStatement(): BelongsTo
    {
        return $this->belongsTo(MedicationStatement::class, 'statement_id');
    }
}
