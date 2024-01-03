<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Identifier extends FhirModel
{
    use HasFactory;

    public function identifiable(): MorphTo
    {
        return $this->morphTo('identifiable');
    }

    public function type(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function assigner(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::IdentifierUse
        ]
    ];

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::v20203
        ]
    ];
}
