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

    public function origin(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'origin');
    }

    public function admitSource(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'admitSource');
    }

    public function reAdmission(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'reAdmission');
    }

    public function dietPreference(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'dietPreference');
    }

    public function specialCourtesy(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'specialCourtesy');
    }

    public function specialArrangement(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'specialArrangement');
    }

    public function destination(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'destination');
    }

    public function dischargeDisposition(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'dischargeDisposition');
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
