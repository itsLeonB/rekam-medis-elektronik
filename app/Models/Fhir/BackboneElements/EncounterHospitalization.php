<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Encounter;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EncounterHospitalization extends FhirModel
{
    use HasFactory;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function preAdmissionIdentifier(): MorphOne
    {
        return $this->morphOne(Identifier::class, 'identifiable');
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }

    public function origin()
    {
        return $this->references()->where('attr_type', 'origin');
    }

    public function codeableConcepts(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
    }

    public function admitSource()
    {
        return $this->codeableConcepts()->where('attr_type', 'admitSource');
    }

    public function reAdmission()
    {
        return $this->codeableConcepts()->where('attr_type', 'reAdmission');
    }

    public function dietPreference()
    {
        return $this->codeableConcepts()->where('attr_type', 'dietPreference');
    }

    public function specialCourtesy()
    {
        return $this->codeableConcepts()->where('attr_type', 'specialCourtesy');
    }

    public function specialArrangement()
    {
        return $this->codeableConcepts()->where('attr_type', 'specialArrangement');
    }

    public function destination()
    {
        return $this->references()->where('attr_type', 'destination');
    }

    public function dischargeDisposition()
    {
        return $this->codeableConcepts()->where('attr_type', 'dischargeDisposition');
    }

    public const ADMIT_SOURCE = [
        'binding' => [
            'valueset' => Codesystems::AdmitSource
        ]
    ];

    public const RE_ADMISSION = [
        'binding' => [
            'valueset' => Codesystems::v20092
        ]
    ];

    public const DIET_PREFERENCE = [
        'binding' => [
            'valueset' => Codesystems::Diet
        ]
    ];

    public const SPECIAL_ARRANGEMENT = [
        'binding' => [
            'valueset' => Codesystems::SpecialArrangements
        ]
    ];

    public const DISCHARGE_DISPOSITION = [
        'binding' => [
            'valueset' => Valuesets::DischargeDisposition
        ]
    ];
}
