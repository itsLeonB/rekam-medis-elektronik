<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    protected $table = 'medication';
    protected $casts = [
        'amount_numerator_value' => 'decimal',
        'amount_denominator_value' => 'decimal',
        'batch_expiration_date' => 'datetime',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function ingredient(): HasMany
    {
        return $this->hasMany(MedicationIngredient::class);
    }
}
