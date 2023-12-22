<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionRelatesTo extends FhirModel
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
