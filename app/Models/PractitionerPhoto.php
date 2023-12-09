<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerPhoto extends Model
{
    protected $table = 'practitioner_photo';
    protected $casts = ['creation' => 'datetime'];
    public $timestamps = false;

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }
}
