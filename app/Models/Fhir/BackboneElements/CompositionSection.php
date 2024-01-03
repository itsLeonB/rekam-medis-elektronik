<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Narrative;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Composition;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CompositionSection extends FhirModel
{
    protected $table = 'composition_section';

    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'code');
    }

    public function author(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'author');
    }

    public function focus(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'focus');
    }

    public function text(): MorphOne
    {
        return $this->morphOne(Narrative::class, 'narrateable');
    }

    public function orderedBy(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'orderedBy');
    }

    public function entry(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'entry');
    }

    public function emptyReason(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'emptyReason');
    }

    public function section(): HasMany
    {
        return $this->hasMany(CompositionSection::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CompositionSection::class, 'parent_id');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::DocumentSectionCodes
        ],
    ];

    public const MODE = [
        'binding' => [
            'valueset' => Codesystems::ListMode
        ],
    ];

    public const ORDERED_BY = [
        'binding' => [
            'valueset' => Codesystems::ListOrderCodes
        ],
    ];

    public const EMPTY_REASON = [
        'binding' => [
            'valueset' => Codesystems::ListEmptyReasons
        ],
    ];
}
