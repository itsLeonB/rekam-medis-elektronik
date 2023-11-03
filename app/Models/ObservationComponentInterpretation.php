<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationComponentInterpretation extends Model
{
    protected $table = 'obs_comp_interpret';
    public $timestamps = false;

    public function observationComponent(): BelongsTo
    {
        return $this->belongsTo(ObservationComponent::class, 'obs_comp_id');
    }
}
