<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Dosage;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class MedicationStatement extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($medicationStatement) {
            $identifier = new Identifier();
            $identifier->system = config('app.identifier_systems.medicationstatement');
            $identifier->use = 'official';
            $identifier->value = Str::uuid();
            $medicationStatement->identifier()->save($identifier);
        });
    }

    protected $table = 'medication_statement';

    protected $casts = [
        'effective_date_time' => 'datetime',
        'date_asserted' => 'datetime'
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function basedOn(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'basedOn');
    }

    public function partOf(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'partOf');
    }

    public function statusReason(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'statusReason');
    }

    public function category(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'category');
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

    public function context(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'context');
    }

    public function effectivePeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'effectivePeriod');
    }

    public function informationSource(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'informationSource');
    }

    public function derivedFrom(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'derivedFrom');
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

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable');
    }

    public function dosage(): MorphMany
    {
        return $this->morphMany(Dosage::class, 'dosageable');
    }

    public const IDENTIFIER = [
        'definition' => 'Identifiers associated with this Medication Statement that are defined by business processes and/or used to refer to it when a direct URL reference to the resource itself is not appropriate. They are business identifiers assigned to this resource by the performer or other systems and remain constant as the resource is updated and propagates from server to server.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'This is a business identifier, not a resource identifier.',
    ];

    public const BASED_ON = [
        'definition' => 'A plan, proposal or order that is fulfilled in whole or in part by this event.',
        'cardinality' => '0...*',
        'type' => 'Reference',
        'validResourceTypes' => ['MedicationRequest', 'CarePlan', 'ServiceRequest',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Allows tracing of authorization for the event and tracking whether proposals/recommendations were acted upon.',
        'comments' => 'References SHALL be a reference to an actual FHIR resource, and SHALL be resolveable (allowing for access control, temporary unavailability, etc.). Resolution can be either by retrieval from the URL, or, where applicable by resource type, by treating an absolute reference as a canonical URL and looking it up in a local registry/repository.',
    ];

    public const PART_OF = [
        'definition' => 'A larger event of which this particular event is a component or step.',
        'cardinality' => '0...*',
        'type' => 'Reference',
        'validResourceTypes' => ['MedicationAdministration', 'MedicationDispense', 'MedicationStatement', 'Procedure', 'Observation',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'This should not be used when indicating which resource a MedicationStatement has been derived from. If that is the use case, then MedicationStatement.derivedFrom should be used.',
        'comments' => 'References SHALL be a reference to an actual FHIR resource, and SHALL be resolveable (allowing for access control, temporary unavailability, etc.). Resolution can be either by retrieval from the URL, or, where applicable by resource type, by treating an absolute reference as a canonical URL and looking it up in a local registry/repository.',
    ];

    public const STATUS = [
        'definition' => 'A code representing the patient or other sources judgment about the state of the medication used that this statement is about. Generally, this will be active or completed.',
        'cardinality' => '1...1',
        'binding' => [
            'desc' => 'A coded concept indicating the current status of a MedicationStatement.',
            'valueset' => Valuesets::MedicationStatusCodes
        ],
        'requirements' => null,
        'comments' => 'MedicationStatement is a statement at a point in time. The status is only representative at the point when it was asserted. The value set for MedicationStatement.status contains codes that assert the status of the use of the medication by the patient (for example, stopped or on hold) as well as codes that assert the status of the medication statement itself (for example, entered in error). This element is labeled as a modifier because the status contains codes that mark the resource as not currently valid.',
    ];

    public const STATUS_REASON = [
        'definition' => 'Captures the reason for the current state of the MedicationStatement.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => 'A coded concept indicating the reason for the status of the statement.',
            'valueset' => Valuesets::SNOMEDCTDrugTherapyStatusCodes
        ],
        'requirements' => null,
        'comments' => 'This is generally only used for "exception" statuses such as "not-taken", "on-hold", "cancelled" or "entered-in-error". The reason for performing the event at all is captured in reasonCode, not here.',
    ];

    public const CATEGORY = [
        'definition' => 'Indicates where the medication is expected to be consumed or administered.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => 'A coded concept identifying where the medication included in the MedicationStatement is expected to be consumed or administered.',
            'valueset' => Valuesets::MedicationUsageCategoryCodes
        ],
        'requirements' => null,
        'comments' => 'Not all terminology uses fit this general pattern. In some cases, models should not use CodeableConcept and use Coding directly and provide their own structure for managing text, codings, translations and the relationship between elements and pre- and post-coordination.',
    ];

    public const MEDICATION = [
        'definition' => 'Identifies the medication being administered. This is either a link to a resource representing the details of the medication or a simple attribute carrying a code that identifies the medication from a known list of medications.',
        'cardinality' => '1...1',
        'binding' => [
            'desc' => 'A coded concept identifying the substance or product being taken.',
            'valueset' => Valuesets::SNOMEDCTMedicationCodes
        ],
        'requirements' => null,
        'comments' => 'If only a code is specified, then it needs to be a code for a specific product. If more information is required, then the use of the medication resource is recommended. For example, if you require form or lot number, then you must reference the Medication resource.',
    ];

    public const SUBJECT = [
        'definition' => 'The person, animal or group who is/was taking the medication.',
        'cardinality' => '1...1',
        'type' => 'Reference',
        'validResourceTypes' => ['Patient', 'Group',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'References SHALL be a reference to an actual FHIR resource, and SHALL be resolveable (allowing for access control, temporary unavailability, etc.). Resolution can be either by retrieval from the URL, or, where applicable by resource type, by treating an absolute reference as a canonical URL and looking it up in a local registry/repository.',
    ];

    public const CONTEXT = [
        'definition' => 'The encounter or episode of care that establishes the context for this MedicationStatement.',
        'cardinality' => '0...1',
        'type' => 'Reference',
        'validResourceTypes' => ['Encounter', 'EpisodeOfCare',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'References SHALL be a reference to an actual FHIR resource, and SHALL be resolveable (allowing for access control, temporary unavailability, etc.). Resolution can be either by retrieval from the URL, or, where applicable by resource type, by treating an absolute reference as a canonical URL and looking it up in a local registry/repository.',
    ];

    public const EFFECTIVE = [
        'definition' => 'The interval of time during which it is being asserted that the patient is/was/will be taking the medication (or was not taking, when the MedicationStatement.taken element is No).',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'This attribute reflects the period over which the patient consumed the medication and is expected to be populated on the majority of Medication Statements. If the medication is still being taken at the time the statement is recorded, the "end" date will be omitted. The date/time attribute supports a variety of dates - year, year/month and exact date. If something more than this is required, this should be conveyed as text.',
    ];

    public const DATE_ASSERTED = [
        'definition' => 'The date when the medication statement was asserted by the information source.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => null,
    ];

    public const INFORMATION_SOURCE = [
        'definition' => 'The person or organization that provided the information about the taking of this medication. Note: Use derivedFrom when a MedicationStatement is derived from other resources, e.g. Claim or MedicationRequest.',
        'cardinality' => '0...1',
        'type' => 'Reference',
        'validResourceTypes' => ['Patient', 'Practitioner', 'PractitionerRole', 'RelatedPerson', 'Organization',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'References SHALL be a reference to an actual FHIR resource, and SHALL be resolveable (allowing for access control, temporary unavailability, etc.). Resolution can be either by retrieval from the URL, or, where applicable by resource type, by treating an absolute reference as a canonical URL and looking it up in a local registry/repository.',
    ];

    public const DERIVED_FROM = [
        'definition' => 'Allows linking the MedicationStatement to the underlying MedicationRequest, or to other information that supports or is used to derive the MedicationStatement.',
        'cardinality' => '0...*',
        'type' => 'Reference',
        'validResourceTypes' => ['Resource',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'Likely references would be to MedicationRequest, MedicationDispense, Claim, Observation or QuestionnaireAnswers. The most common use cases for deriving a MedicationStatement comes from creating a MedicationStatement from a MedicationRequest or from a lab observation or a claim. it should be noted that the amount of information that is available varies from the type resource that you derive the MedicationStatement from.',
    ];

    public const REASON_REFERENCE = [
        'definition' => 'Condition or observation that supports why the medication is being/was taken.',
        'cardinality' => '0...*',
        'type' => 'Reference',
        'validResourceTypes' => ['Condition', 'Observation', 'DiagnosticReport',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'This is a reference to a condition that is the reason why the medication is being/was taken. If only a code exists, use reasonForUseCode.',
    ];

    public const NOTE = [
        'definition' => 'Provides extra information about the medication statement that is not conveyed by the other attributes.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'For systems that do not have structured annotations, they can simply communicate a single annotation with no author or time. This element may need to be included in narrative because of the potential for modifying information. Annotations SHOULD NOT be used to communicate "modifying" information that could be computable. (This is a SHOULD because enforcing user behavior is nearly impossible).',
    ];

    public const DOSAGE = [
        'definition' => 'Indicates how the medication is/was or should be taken by the patient.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'The dates included in the dosage on a Medication Statement reflect the dates for a given dose. For example, "from November 1, 2016 to November 3, 2016, take one tablet daily and from November 4, 2016 to November 7, 2016, take two tablets daily." It is expected that this specificity may only be populated where the patient brings in their labeled container or where the Medication Statement is derived from a MedicationRequest.',
    ];
}
