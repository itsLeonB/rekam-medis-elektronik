<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Composition;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CompositionAttester extends FhirModel
{
    protected $table = 'composition_attester';

    protected $casts = ['time' => 'datetime'];

    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public function party(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public const MODE = [
        'binding' => [
            'valueset' => Codesystems::CompositionAttestationMode
        ],
    ];
}
