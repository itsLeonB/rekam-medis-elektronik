<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($serviceRequest) {
            $orgId = config('app.organization_id');

            $identifier = new ServiceRequestIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/servicerequest/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $serviceRequest->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $serviceRequest->identifier()->save($identifier);
        });
    }

    protected $table = 'service_request';
    protected $casts = [
        'based_on' => 'array',
        'replaces' => 'array',
        'category' => 'array',
        'do_not_perform' => 'boolean',
        'order_detail' => 'array',
        'quantity' => 'array',
        'occurrence' => 'array',
        'as_needed' => 'array',
        'authored_on' => 'datetime',
        'performer' => 'array',
        'location_code' => 'array',
        'location_reference' => 'array',
        'reason_code' => 'array',
        'reason_reference' => 'array',
        'insurance' => 'array',
        'supporting_info' => 'array',
        'specimen' => 'array',
        'body_site' => 'array',
        'relevant_history' => 'array',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ServiceRequestIdentifier::class, 'request_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(ServiceRequestNote::class, 'request_id');
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::RequestStatus
        ]
    ];

    public const INTENT = [
        'binding' => [
            'valueset' => Codesystems::RequestIntent
        ]
    ];

    public const CATEGORY = [
        'binding' => [
            'valueset' => Valuesets::ServiceRequestCategoryCodes
        ]
    ];

    public const PRIORITY = [
        'binding' => [
            'valueset' => Codesystems::RequestPriority
        ]
    ];

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::ServiceRequestCodes
        ]
    ];

    public const ORDER_DETAIL = [
        'binding' => [
            'valueset' => Valuesets::ServiceRequestOrderDetailsCodes
        ]
    ];

    public const QUANTITY = [
        'variableTypes' => ['quantityQuantity', 'quantityRatio', 'quantityRange']
    ];

    public const OCCURRENCE = [
        'variableTypes' => ['occurrenceDateTime', 'occurrencePeriod', 'occurrenceTiming']
    ];

    public const AS_NEEDED = [
        'variableTypes' => ['asNeededBoolean', 'asNeededCodeableConcept'],
        'binding' => [
            'valueset' => Codesystems::ICD10
        ]
    ];

    public const PERFORMER_TYPE = [
        'binding' => [
            'valueset' => Valuesets::ParticipantRoles
        ]
    ];

    public const LOCATION_CODE = [
        'binding' => [
            'valueset' => Valuesets::ServiceRequestLocationCode
        ]
    ];

    public const REASON_CODE = [
        'binding' => [
            'valueset' => Codesystems::ICD10
        ]
    ];

    public const BODY_SITE = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTBodySite
        ]
    ];
}
