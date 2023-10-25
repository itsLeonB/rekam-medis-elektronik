<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergyIntoleranceReactionNote extends Model
{
    protected $table = 'allergy_react_note';
    protected $casts = [
        'author' => 'json',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function reaction(): BelongsTo
    {
        return $this->belongsTo(AllergyIntoleranceReaction::class, 'allergy_react_id');
    }
}
