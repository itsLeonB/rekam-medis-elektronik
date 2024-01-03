<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Resources\MedicationRequest;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MedicationRequestSubstitution extends FhirModel
{
    use HasFactory;

    protected $table = 'med_req_substitution';

    protected $casts = ['allowed_boolean' => 'boolean'];

    public $timestamps = false;

    public function medicationRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'med_req_id');
    }

    public function allowedCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'allowed');
    }

    public function reason(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'reason');
    }

    public const ALLOWED = [
        'binding' => [
            'valueset' => Codesystems::v3SubstanceAdminSubstitution
        ]
    ];

    public const REASON = [
        'binding' => [
            'valueset' => Codesystems::v3ActReason
        ]
    ];
}
