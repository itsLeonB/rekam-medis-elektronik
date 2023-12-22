<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends FhirModel
{
    use HasFactory;

    protected $table = 'address';

    protected $casts = ['line' => 'array'];

    protected $with = ['period', 'administrativeCode', 'geolocation'];

    public function addressable(): MorphTo
    {
        return $this->morphTo('addressable');
    }

    public function complexExtension(): MorphMany
    {
        return $this->morphMany(ComplexExtension::class, 'complex_extendable');
    }

    public function administrativeCode()
    {
        return $this->complexExtension()->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode');
    }

    public function geolocation()
    {
        return $this->complexExtension()->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/geolocation');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::AddressUse
        ]
    ];

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::AddressType
        ]
    ];

    public const COUNTRY = [
        'binding' => [
            'valueset' => Codesystems::ISO3166
        ]
    ];

    public const ADMINISTRATIVE_CODE = [
        'binding' => [
            'valueset' => Codesystems::AdministrativeArea
        ]
    ];
}
