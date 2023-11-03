<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationBasedOn extends Model
{
    protected $table = 'observation_based_on';
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
