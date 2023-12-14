<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionnaireResponse extends FhirModel
{
    use HasFactory;

    protected $table = 'questionnaire_response';
    protected $casts = [
        'based_on' => 'array',
        'part_of' => 'array',
        'authored' => 'datetime'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function item(): HasMany
    {
        return $this->hasMany(QuestionnaireResponseItem::class, 'questionnaire_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($questionnaireResponse) {
            $orgId = config('app.organization_id');
            $questionnaireResponse->identifier_system = 'http://sys-ids.kemkes.go.id/questionnaireresponse/' . $orgId;
            $questionnaireResponse->identifier_use = 'official';

            // Get the maximum value of identifier_value from the database
            $maxIdentifierValue = static::max('identifier_value') ?? 0;

            // Increment the value and set it
            $questionnaireResponse->identifier_value = $maxIdentifierValue + 1;
        });
    }


    public const IDENTIFIER = [
        'definition' => 'A business identifier assigned to a particular completed (or partially completed) questionnaire.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Used for tracking, registration and other business purposes.',
        'comments' => null,
    ];

    public const BASED_ON = [
        'definition' => 'The order, proposal or plan that is fulfilled in whole or in part by this QuestionnaireResponse. For example, a ServiceRequest seeking an intake assessment or a decision support recommendation to assess for post-partum depression.',
        'cardinality' => '0...*',
        'type' => 'Reference',
        'validResourceTypes' => ['CarePlan', 'ServiceRequest',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Supports traceability of responsibility for the action and allows linkage of an action to the recommendations acted upon.',
        'comments' => 'References SHALL be a reference to an actual FHIR resource, and SHALL be resolveable (allowing for access control, temporary unavailability, etc.). Resolution can be either by retrieval from the URL, or, where applicable by resource type, by treating an absolute reference as a canonical URL and looking it up in a local registry/repository.',
    ];

    public const PART_OF = [
        'definition' => 'A procedure or observation that this questionnaire was performed as part of the execution of. For example, the surgery a checklist was executed as part of.',
        'cardinality' => '0...*',
        'type' => 'Reference',
        'validResourceTypes' => ['Observation', 'Procedure',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'Composition of questionnaire responses will be handled by the parent questionnaire having answers that reference the child questionnaire. For relationships to referrals, and other types of requests, use basedOn.',
    ];

    public const QUESTIONNAIRE = [
        'definition' => 'The Questionnaire that defines and organizes the questions for which answers are being provided.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Needed to allow editing of the questionnaire response in a manner that enforces the constraints of the original form.',
        'comments' => 'If a QuestionnaireResponse references a Questionnaire, then the QuestionnaireResponse structure must be consistent with the Questionnaire (i.e. questions must be organized into the same groups, nested questions must still be nested, etc.).',
    ];

    public const STATUS = [
        'definition' => 'The position of the questionnaire response within its overall lifecycle.',
        'cardinality' => '1...1',
        'binding' => [
            'desc' => 'Lifecycle status of the questionnaire response.',
            'valueset' => Valuesets::QuestionnaireResponseStatus
        ],
        'requirements' => 'The information on Questionnaire resources may possibly be gathered during multiple sessions and altered after considered being finished.',
        'comments' => 'This element is labeled as a modifier because the status contains codes that mark the resource as not currently valid.',
    ];

    public const SUBJECT = [
        'definition' => 'The subject of the questionnaire response. This could be a patient, organization, practitioner, device, etc. This is who/what the answers apply to, but is not necessarily the source of information.',
        'cardinality' => '0...1',
        'type' => 'Reference',
        'validResourceTypes' => ['Resource',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Allows linking the answers to the individual the answers describe. May also affect access control.',
        'comments' => 'If the Questionnaire declared a subjectType, the resource pointed to by this element must be an instance of one of the listed types.',
    ];

    public const ENCOUNTER = [
        'definition' => 'The Encounter during which this questionnaire response was created or to which the creation of this record is tightly associated.',
        'cardinality' => '0...1',
        'type' => 'Reference',
        'validResourceTypes' => ['Encounter',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Provides context for the information that was captured. May also affect access control.',
        'comments' => 'This will typically be the encounter the event occurred within, but some activities may be initiated prior to or after the official completion of an encounter but still be tied to the context of the encounter. A questionnaire that was initiated during an encounter but not fully completed during the encounter would still generally be associated with the encounter.',
    ];

    public const AUTHORED = [
        'definition' => 'The date and/or time that this set of answers were last changed.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Clinicians need to be able to check the date that the information in the questionnaire was collected, to derive the context of the answers.',
        'comments' => 'May be different from the lastUpdateTime of the resource itself, because that reflects when the data was known to the server, not when the data was captured. This element is optional to allow for systems that might not know the value, however it SHOULD be populated if possible.',
    ];

    public const AUTHOR = [
        'definition' => 'Person who received the answers to the questions in the QuestionnaireResponse and recorded them in the system.',
        'cardinality' => '0...1',
        'type' => 'Reference',
        'validResourceTypes' => ['Device', 'Practitioner', 'PractitionerRole', 'Patient', 'RelatedPerson', 'Organization',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Need to know who interpreted the subjects answers to the questions in the questionnaire, and selected the appropriate options for answers.',
        'comments' => 'Mapping a subjects answers to multiple choice options and determining what to put in the textual answer is a matter of interpretation. Authoring by device would indicate that some portion of the questionnaire had been auto-populated.',
    ];

    public const SOURCE = [
        'definition' => 'The person who answered the questions about the subject.',
        'cardinality' => '0...1',
        'type' => 'Reference',
        'validResourceTypes' => ['Patient', 'Practitioner', 'PractitionerRole', 'RelatedPerson',],
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'When answering questions about a subject that is minor, incapable of answering or an animal, another human source may answer the questions.',
        'comments' => 'If not specified, no inference can be made about who provided the data.',
    ];

    public const ITEM = [
        'definition' => 'A group or question item from the original questionnaire for which answers are provided.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'Groups cannot have answers and therefore must nest directly within item. When dealing with questions, nesting must occur within each answer because some questions may have multiple answers (and the nesting occurs for each answer).',
    ];
}
