<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionSectionAuthor extends Model
{
    protected $table = 'composition_section_author';
    public $timestamps = false;

    public function section(): BelongsTo
    {
        return $this->belongsTo(CompositionSection::class);
    }
}
