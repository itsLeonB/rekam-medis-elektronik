<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\MedicationRequestDispenseRequest;
use App\Models\Fhir\BackboneElements\MedicationRequestSubstitution;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Dosage;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class MedicationRequest extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($medicationRequest) {
            $identifier = new Identifier();
            $identifier->system = config('app.identifier_systems.medicationrequest');
            $identifier->use = 'official';
            $identifier->value = Str::uuid();
            $medicationRequest->identifier()->save($identifier);
        });
    }

    protected $table = 'medication_request';

    protected $casts = [
        'do_not_perform' => 'boolean',
        'reported_boolean' => 'boolean',
        'authored_on' => 'datetime',
        'instantiates_canonical' => 'array',
        'instantiates_uri' => 'array',
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable')
            ->where('attr_type', 'identifier');
    }

    public function statusReason(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'statusReason');
    }

    public function category(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'category');
    }

    public function reportedReference(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'reportedReference');
    }

    public function medicationCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'medicationCodeableConcept');
    }

    public function medicationReference(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'medicationReference');
    }

    public function subject(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'subject');
    }

    public function encounter(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'encounter');
    }

    public function supportingInformation(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'supportingInformation');
    }

    public function requester(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'requester');
    }

    public function performer(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'performer');
    }

    public function performerType(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'performerType');
    }

    public function recorder(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'recorder');
    }

    public function reasonCode(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'reasonCode');
    }

    public function reasonReference(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'reasonReference');
    }

    public function basedOn(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'basedOn');
    }

    public function groupIdentifier(): MorphOne
    {
        return $this->morphOne(Identifier::class, 'identifiable')
            ->where('attr_type', 'groupIdentifier');
    }

    public function courseOfTherapyType(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'courseOfTherapyType');
    }

    public function insurance(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'insurance');
    }

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable')
            ->where('attr_type', 'note');
    }

    public function dosageInstruction(): MorphMany
    {
        return $this->morphMany(Dosage::class, 'dosageable')
            ->where('attr_type', 'dosageInstruction');
    }

    public function dispenseRequest(): HasOne
    {
        return $this->hasOne(MedicationRequestDispenseRequest::class, 'med_req_id');
    }

    public function substitution(): HasOne
    {
        return $this->hasOne(MedicationRequestSubstitution::class, 'med_req_id');
    }

    public function priorPrescription(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'priorPrescription');
    }

    public function detectedIssue(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'detectedIssue');
    }

    public function eventHistory(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'eventHistory');
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::MedicationRequestStatus
        ]
    ];

    public const STATUS_REASON = [
        'binding' => [
            'valueset' => Codesystems::MedicationRequestStatusReasonCodes
        ]
    ];

    public const INTENT = [
        'binding' => [
            'valueset' => Codesystems::MedicationRequestIntent
        ]
    ];

    public const CATEGORY = [
        'binding' => [
            'valueset' => Codesystems::MedicationRequestCategoryCodes
        ]
    ];

    public const PRIORITY = [
        'binding' => [
            'valueset' => Codesystems::RequestPriority
        ]
    ];

    public const REPORTED = [
        'variableTypes' => ['reportedBoolean', 'reportedReference']
    ];

    public const PERFORMER_TYPE = [
        'binding' => [
            'valueset' => Valuesets::ProcedurePerformerRoleCodes
        ]
    ];

    public const REASON_CODE = [
        'binding' => [
            'valueset' => Codesystems::ICD10
        ]
    ];

    public const COURSE_OF_THERAPY_TYPE = [
        'binding' => [
            'valueset' => Codesystems::MedicationRequestCourseOfTherapyCodes
        ]
    ];

    public const DISPENSE_INTERVAL = [
        'binding' => [
            'valueset' => Valuesets::MedicationRequestDispenseInterval
        ]
    ];

    public const QUANTITY = [
        'binding' => [
            'valueset' => [Valuesets::MedicationIngredientStrengthDenominator, Valuesets::MedicationRequestQuantity]
        ]
    ];

    public const SUPPLY_DURATION = [
        'binding' => [
            'valueset' => Valuesets::MedicationRequestSupplyDuration
        ]
    ];

    public const SUBSTITUTION_ALLOWED = [
        'binding' => [
            'valueset' => Codesystems::v3SubstanceAdminSubstitution
        ],
        'variableTypes' => ['allowedBoolean', 'allowedCodeableConcept']
    ];

    public const SUBSTITUTION_REASON = [
        'binding' => [
            'valueset' => Valuesets::MedicationRequestSubstitutionReason
        ]
    ];
}
