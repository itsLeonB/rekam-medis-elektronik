<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AllergyIntoleranceReaction extends Model
{
    protected $table = 'allergy_intolerance_reaction';
    protected $casts = ['onset' => 'datetime'];
    public $timestamps = false;

    public function allergy(): BelongsTo
    {
        return $this->belongsTo(AllergyIntolerance::class, 'allergy_id');
    }

    public function manifestation(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceReactionManifestation::class, 'allergy_reaction_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceReactionNote::class, 'allergy_reaction_id');
    }
}
