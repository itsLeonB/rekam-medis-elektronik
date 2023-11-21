<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecimenNote extends Model
{
    protected $table = 'specimen_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function specimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class);
    }
}
