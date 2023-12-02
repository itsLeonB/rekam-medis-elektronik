<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationStatementIdentifier extends Model
{
    protected $table = 'medication_statement_identifier';
    public $timestamps = false;

    public function medicationStatement(): BelongsTo
    {
        return $this->belongsTo(MedicationStatement::class, 'statement_id');
    }
}
