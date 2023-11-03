<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedureFollowUp extends Model
{
    protected $table = 'procedure_follow_up';
    public $timestamps = false;

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }
}
