<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionRelatesTo extends Model
{
    protected $table = 'composition_relates_to';
    protected $casts = ['target' => 'json'];
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }
}
