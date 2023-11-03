<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedureIdentifier extends Model
{
    protected $table = 'procedure_identifier';
    public $timestamps = false;

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }
}
