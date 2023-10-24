<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionEventCode extends Model
{
    protected $table = 'composition_event_code';
    public $timestamps = false;

    public function event(): BelongsTo
    {
        return $this->belongsTo(CompositionEvent::class, 'composition_event_id');
    }
}
