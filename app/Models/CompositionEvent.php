<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompositionEvent extends Model
{
    protected $table = 'composition_event';
    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public function code(): HasMany
    {
        return $this->hasMany(CompositionEventCode::class, 'composition_event_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(CompositionEventDetail::class, 'composition_event_id');
    }
}
