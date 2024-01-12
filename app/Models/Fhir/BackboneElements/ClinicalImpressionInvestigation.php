<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\ClinicalImpression;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ClinicalImpressionInvestigation extends FhirModel
{
    use HasFactory;

    protected $table = 'clinical_impression_investigation';

    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function item(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::InvestigationType
        ],
    ];
}
