<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\Fhir\Datatypes\Reference;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Annotation extends FhirModel
{
    use HasFactory;

    protected $casts = ['time' => 'datetime'];

    public function annotable()
    {
        return $this->morphTo('annotable');
    }

    public function authorReference(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }
}
