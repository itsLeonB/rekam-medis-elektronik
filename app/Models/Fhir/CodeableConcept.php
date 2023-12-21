<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CodeableConcept extends FhirModel
{
    use HasFactory;

    protected $with = ['coding'];

    public function codeable(): MorphTo
    {
        return $this->morphTo('codeable');
    }

    public function coding(): MorphMany
    {
        return $this->morphMany(Coding::class, 'codeable');
    }
}
