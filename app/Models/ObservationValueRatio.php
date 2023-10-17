<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationValueRatio extends Model
{
    protected $table = 'observation_val_ratio';
    protected $casts = [
        'value_numerator' => 'decimal',
        'value_denominator' => 'decimal'
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
