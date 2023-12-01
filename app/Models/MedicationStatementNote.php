<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationStatementNote extends Model
{
    protected $table = 'medication_statement_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function medicationStatement(): BelongsTo
    {
        return $this->belongsTo(MedicationStatement::class, 'statement_id');
    }
}
