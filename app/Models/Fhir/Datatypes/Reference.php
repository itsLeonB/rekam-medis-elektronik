<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reference extends FhirModel
{
    use HasFactory;

    public function referenceable(): MorphTo
    {
        return $this->morphTo('referenceable');
    }

    public function identifier(): MorphOne
    {
        return $this->morphOne(Identifier::class, 'identifiable');
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::ResourceType
        ]
    ];
}
