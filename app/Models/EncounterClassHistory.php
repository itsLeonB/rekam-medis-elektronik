<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterClassHistory extends Model
{
    protected $table = 'encounter_class_history';
    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
