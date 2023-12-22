<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationStatementReasonCode extends FhirModel
{
    protected $table = 'medication_statement_reason_code';
    public $timestamps = false;

    public function medicationStatement(): BelongsTo
    {
        return $this->belongsTo(MedicationStatement::class, 'statement_id');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::ConditionProblemDiagnosisCodes
        ],
    ];
}
