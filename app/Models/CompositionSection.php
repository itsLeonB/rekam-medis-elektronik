<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompositionSection extends Model
{
    protected $table = 'composition_section';
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public function author(): HasMany
    {
        return $this->hasMany(CompositionSectionAuthor::class, 'composition_section_id');
    }

    public function entry(): HasMany
    {
        return $this->hasMany(CompositionSectionEntry::class, 'composition_section_id');
    }
}
