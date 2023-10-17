<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationComponentValueSample extends Model
{
    protected $table = 'obs_comp_val_sample';
    protected $casts = [
        'origin_value' => 'decimal',
        'period' => 'decimal',
        'factor' => 'decimal',
        'lower_limit' => 'decimal',
        'upper_limit' => 'decimal',
    ];
    public $timestamps = false;

    public function observationComponent(): BelongsTo
    {
        return $this->belongsTo(ObservationComponent::class, 'obs_comp_id');
    }
}
