<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionRelatesTo extends Model
{
   protected $table = 'composition_relates_to';
    protected $casts = ['target' => 'array'];
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public const CODE = [
        'binding' => [
            'valueset' => Codesystems::DocumentRelationshipType
        ],
    ];
}
