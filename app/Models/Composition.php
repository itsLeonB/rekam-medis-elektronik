<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Composition extends Model
{
    protected $table = 'composition';
    protected $casts = ['date' => 'datetime'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function category(): HasMany
    {
        return $this->hasMany(CompositionCategory::class);
    }

    public function author(): HasMany
    {
        return $this->hasMany(CompositionAuthor::class);
    }

    public function attester(): HasMany
    {
        return $this->hasMany(CompositionAttester::class);
    }

    public function relatesTo(): HasMany
    {
        return $this->hasMany(CompositionRelatesTo::class);
    }

    public function event(): HasMany
    {
        return $this->hasMany(CompositionEvent::class);
    }

    public function section(): HasMany
    {
        return $this->hasMany(CompositionSection::class);
    }
}
