<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationIngredient extends Model
{
    protected $table = 'medication_ingredient';
    protected $casts = [
        'is_active' => 'boolean',
        'strength_numerator_value' => 'decimal',
        'strength_denominator_value' => 'decimal'
    ];
    public $timestamps = false;

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }
}
