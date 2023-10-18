<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Observation extends Model
{
    protected $table = 'observation';
    protected $casts = [
        'effective' => 'json',
        'issued' => 'datetime',
        'value' => 'json',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ObservationIdentifier::class);
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(ObservationBasedOn::class);
    }

    public function partOf(): HasMany
    {
        return $this->hasMany(ObservationPartOf::class);
    }

    public function category(): HasMany
    {
        return $this->hasMany(ObservationCategory::class);
    }

    public function focus(): HasMany
    {
        return $this->hasMany(ObservationFocus::class);
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ObservationPerformer::class);
    }

    public function interpretation(): HasMany
    {
        return $this->hasMany(ObservationInterpretation::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ObservationNote::class);
    }

    public function referenceRange(): HasMany
    {
        return $this->hasMany(ObservationReferenceRange::class);
    }

    public function member(): HasMany
    {
        return $this->hasMany(ObservationMember::class);
    }

    public function derivedFrom(): HasMany
    {
        return $this->hasMany(ObservationDerivedFrom::class);
    }

    public function component(): HasMany
    {
        return $this->hasMany(ObservationComponent::class);
    }
}
