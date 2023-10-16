<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterReason extends Model
{
    protected $table = 'encounter_reason';
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
