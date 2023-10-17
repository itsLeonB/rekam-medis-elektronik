<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ObservationComponent extends Model
{
    protected $table = 'observation_component';
    protected $casts = [
        'value_boolean' => 'boolean',
        'value_time' => 'time',
        'value_datetime' => 'datetime',
        'value_start' => 'datetime',
        'value_end' => 'datetime'
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }

    public function interpretation(): HasMany
    {
        return $this->hasMany(ObservationComponentInterpretation::class, 'obs_comp_id');
    }

    public function referenceRange(): HasMany
    {
        return $this->hasMany(ObservationComponentReferenceRange::class, 'obs_comp_id');
    }

    public function valueRatio(): HasOne
    {
        return $this->hasOne(ObservationComponentValueRatio::class, 'obs_comp_id');
    }

    public function valueRange(): HasOne
    {
        return $this->hasOne(ObservationComponentValueRange::class, 'obs_comp_id');
    }

    public function valueQuantity(): HasOne
    {
        return $this->hasOne(ObservationComponentValueQuantity::class, 'obs_comp_id');
    }

    public function valueSample(): HasOne
    {
        return $this->hasOne(ObservationComponentValueSample::class, 'obs_comp_id');
    }
}
