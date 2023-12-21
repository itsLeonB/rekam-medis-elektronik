<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends FhirModel
{
    use HasFactory;

    protected $table = 'address';

    public function addressable(): MorphTo
    {
        return $this->morphTo('addressable');
    }

    public function complexExtension(): MorphMany
    {
        return $this->morphMany(ComplexExtension::class, 'complex_extendable');
    }

    public function administrativeCode(): MorphOne
    {
        return $this->complexExtension()->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode');
    }

    public function geolocation(): MorphOne
    {
        return $this->complexExtension()->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/geolocation');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }
}
