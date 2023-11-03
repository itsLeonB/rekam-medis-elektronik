<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ObservationComponent extends Model
{
    protected $table = 'observation_component';
    protected $casts = ['value' => 'json'];
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
}
