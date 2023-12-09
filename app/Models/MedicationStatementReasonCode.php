<?php

namespace App\Models;

use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationStatementReasonCode extends Model
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
