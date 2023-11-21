<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecimenIdentifier extends Model
{
    protected $table = 'specimen_identifier';
    public $timestamps = false;

    public function specimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class);
    }
}
