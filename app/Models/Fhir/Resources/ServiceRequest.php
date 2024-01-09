<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Quantity;
use App\Models\Fhir\Datatypes\Range;
use App\Models\Fhir\Datatypes\Ratio;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Datatypes\Timing;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class ServiceRequest extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($serviceRequest) {
            $identifier = new Identifier();
            $identifier->system = config('app.identifier_systems.servicerequest');
            $identifier->use = 'official';
            $identifier->value = Str::uuid();
            $serviceRequest->identifier()->save($identifier);
        });
    }

    protected $table = 'service_request';

    protected $casts = [
        'instantiates_canonical' => 'array',
        'instantiates_uri' => 'array',
        'do_not_perform' => 'boolean',
        'occurrence_date_time' => 'datetime',
        'as_needed_boolean' => 'boolean',
        'authored_on' => 'datetime',
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

    public function basedOn(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'basedOn');
    }

    public function replaces(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'replaces');
    }

    public function requisition(): MorphOne
    {
        return $this->morphOne(Identifier::class, 'identifiable')
            ->where('attr_type', 'requisition');
    }

    public function category(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'category');
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'code');
    }

    public function orderDetail(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'orderDetail');
    }

    public function quantityQuantity(): MorphOne
    {
        return $this->morphOne(Quantity::class, 'quantifiable')
            ->where('attr_type', 'quantityQuantity');
    }

    public function quantityRatio(): MorphOne
    {
        return $this->morphOne(Ratio::class, 'rateable')
            ->where('attr_type', 'quantityRatio');
    }

    public function quantityRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable')
            ->where('attr_type', 'quantityRange');
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

    public function occurrencePeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'occurrencePeriod');
    }

    public function occurrenceTiming(): MorphOne
    {
        return $this->morphOne(Timing::class, 'timeable')
            ->where('attr_type', 'occurrenceTiming');
    }

    public function asNeededCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'asNeededCodeableConcept');
    }

    public function requester(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'requester');
    }

    public function performerType(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'performerType');
    }

    public function performer(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'performer');
    }

    public function locationCode(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'locationCode');
    }

    public function locationReference(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'locationReference');
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

    public function insurance(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'insurance');
    }

    public function supportingInfo(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'supportingInfo');
    }

    public function specimen(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'specimen');
    }

    public function bodySite(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'bodySite');
    }

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable');
    }

    public function relevantHistory(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'relevantHistory');
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
