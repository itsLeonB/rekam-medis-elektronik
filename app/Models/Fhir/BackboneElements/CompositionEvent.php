<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Composition;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CompositionEvent extends FhirModel
{
    use HasFactory;

    protected $table = 'composition_event';

    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public function code(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function detail(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Codesystems::v3ActCode
        ],
    ];
}
