<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergyIntoleranceIdentifier extends Model
{
    protected $table = 'allergy_intolerance_identifier';
    public $timestamps = false;

    public function allergy(): BelongsTo
    {
        return $this->belongsTo(AllergyIntolerance::class, 'allergy_id');
    }
}
