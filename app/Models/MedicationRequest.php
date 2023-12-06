<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationRequest extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($medicationRequest) {
            $orgId = config('app.organization_id');

            $identifier = new MedicationRequestIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/prescription/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $medicationRequest->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $medicationRequest->identifier()->save($identifier);
        });
    }

    protected $table = 'medication_request';
    protected $casts = [
        'category' => 'array',
        'do_not_perform' => 'boolean',
        'reported' => 'boolean',
        'supporting_information' => 'array',
        'authored_on' => 'datetime',
        'reason_code' => 'array',
        'reason_reference' => 'array',
        'based_on' => 'array',
        'insurance' => 'array',
        'dispense_interval_value' => 'decimal:2',
        'validity_period_start' => 'datetime',
        'validity_period_end' => 'datetime',
        'quantity_value' => 'decimal:2',
        'supply_duration_value' => 'decimal:2',
        'substitution_allowed' => 'array'
    ];
    public $timestamps = false;
    protected $with = ['identifier', 'note', 'dosage'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(MedicationRequestIdentifier::class, 'med_req_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(MedicationRequestNote::class, 'med_req_id');
    }

    public function dosage(): HasMany
    {
        return $this->hasMany(MedicationRequestDosage::class, 'med_req_id');
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
