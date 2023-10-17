<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationValueSample extends Model
{
    protected $table = 'observation_val_sample';
    protected $casts = [
        'origin_value' => 'decimal',
        'period' => 'decimal',
        'factor' => 'decimal',
        'lower_limit' => 'decimal',
        'upper_limit' => 'decimal',
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
