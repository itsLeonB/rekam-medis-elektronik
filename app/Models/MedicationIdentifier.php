<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationIdentifier extends Model
{
    protected $table = 'medication_identifier';
    public $timestamps = false;

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }
}
