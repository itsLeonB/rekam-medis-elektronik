<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationComponentValueRatio extends Model
{
    protected $table = 'obs_comp_val_ratio';
    protected $casts = [
        'value_numerator' => 'decimal',
        'value_denominator' => 'decimal'
    ];
    public $timestamps = false;

    public function observationComponent(): BelongsTo
    {
        return $this->belongsTo(ObservationComponent::class, 'obs_comp_id');
    }
}
