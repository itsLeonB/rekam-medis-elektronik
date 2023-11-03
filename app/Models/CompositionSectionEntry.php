<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionSectionEntry extends Model
{
    protected $table = 'composition_section_entry';
    public $timestamps = false;

    public function section(): BelongsTo
    {
        return $this->belongsTo(CompositionSection::class);
    }
}
