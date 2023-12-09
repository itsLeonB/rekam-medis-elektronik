<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedureNote extends Model
{
    protected $table = 'procedure_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }
}
