<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Composition;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CompositionRelatesTo extends FhirModel
{
    protected $table = 'composition_relates_to';

    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public function targetIdentifier(): MorphOne
    {
        return $this->morphOne(Identifier::class, 'identifiable');
    }

    public function targetReference(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Codesystems::DocumentRelationshipType
        ],
    ];
}
