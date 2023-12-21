<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ContactPoint extends FhirModel
{
    use HasFactory;

    protected $with = ['period'];

    public function contactPointable()
    {
        return $this->morphTo('contact_pointable');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }
}
