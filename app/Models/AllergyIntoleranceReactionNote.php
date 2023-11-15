<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergyIntoleranceReactionNote extends Model
{
    protected $table = 'allergy_react_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function reaction(): BelongsTo
    {
        return $this->belongsTo(AllergyIntoleranceReaction::class, 'allergy_react_id');
    }
}
