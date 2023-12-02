<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationNote extends Model
{
    protected $table = 'observation_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
