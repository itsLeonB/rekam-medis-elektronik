<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterParticipant extends Model
{
    protected $table = 'encounter_participant';
    protected $casts = ['type' => 'array'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
