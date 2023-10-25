<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AllergyIntolerance extends Model
{
    protected $table = 'allergy_intolerance';
    protected $casts = [
        'onset' => 'json',
        'recorder_date' => 'datetime',
        'last_occurence' => 'datetime',
        'category_food' => 'boolean',
        'category_medication' => 'boolean',
        'category_environment'  => 'boolean',
        'category_biologic' => 'boolean'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceIdentifier::class, 'allergy_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceNote::class, 'allergy_id');
    }

    public function reaction(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceReaction::class, 'allergy_id');
    }
}
