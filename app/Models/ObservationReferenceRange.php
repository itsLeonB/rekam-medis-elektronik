<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationReferenceRange extends Model
{
    protected $table = 'observation_ref_range';
    protected $casts = [
        'value_low' => 'decimal',
        'value_high' => 'decimal'
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
