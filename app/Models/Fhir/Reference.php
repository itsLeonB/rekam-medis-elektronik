<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reference extends FhirModel
{
    use HasFactory;

    protected $with = ['identifier'];

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
