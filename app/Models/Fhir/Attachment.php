<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    use HasFactory;

    protected $casts = ['creation' => 'datetime'];

    public function attachable(): MorphTo
    {
        return $this->morphTo('attachable');
    }
}
