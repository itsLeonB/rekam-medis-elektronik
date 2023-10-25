<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergyIntoleranceNote extends Model
{
    protected $table = 'allergy_intolerance_note';
    protected $casts = [
        'author' => 'json',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function allergy(): BelongsTo
    {
        return $this->belongsTo(AllergyIntolerance::class, 'allergy_id');
    }
}
