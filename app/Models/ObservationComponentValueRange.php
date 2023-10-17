<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationComponentValueRange extends Model
{
    protected $table = 'obs_comp_val_range';
    protected $casts = [
        'value_low' => 'decimal',
        'value_high' => 'decimal'
    ];
    public $timestamps = false;

    public function observationComponent(): BelongsTo
    {
        return $this->belongsTo(ObservationComponent::class, 'obs_comp_id');
    }
}
