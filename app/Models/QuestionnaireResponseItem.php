<?php

namespace App\Models;

use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireResponseItem extends Model
{
    protected $table = 'questionnaire_response_item';
    public $timestamps = false;
    protected $casts = [
        'answer' => 'array',
        'item' => 'array'
    ];

    public function questionnaireResponse(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireResponse::class, 'questionnaire_id');
    }

    public const LINK_ID = [
        'definition' => 'The item from the Questionnaire that corresponds to this item in the QuestionnaireResponse resource.',
        'cardinality' => '1...1',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Items can repeat in the answers, so a direct 1..1 correspondence by position might not exist - requiring correspondence by identifier.',
        'comments' => 'Note that FHIR strings SHALL NOT exceed 1MB in size',
    ];

    public const DEFINITION = [
        'definition' => 'A reference to an [ElementDefinition](elementdefinition.html) that provides the details for the item.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'A common pattern is to define a set of data elements, and then build multiple different questionnaires for different circumstances to gather the data. This element provides traceability to the common definition.',
        'comments' => 'The ElementDefinition must be in a StructureDefinition, and must have a fragment identifier that identifies the specific data element by its id (Element.id). E.g. https://fhir.kemkes.go.id/r4/StructureDefinition/Observation#Observation.value[x]. There is no need for this element if the item pointed to by the linkId has a definition listed.',
    ];

    public const TEXT = [
        'definition' => 'Text that is displayed above the contents of the group or as the text of the question being answered.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Allows the questionnaire response to be read without access to the questionnaire.',
        'comments' => 'Note that FHIR strings SHALL NOT exceed 1MB in size',
    ];

    public const ANSWER = [
        'definition' => 'The respondents answer(s) to the question.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'The value is nested because we cannot have a repeating structure that has variable type.',
    ];

    public const ANSWER_VALUE = [
        'definition' => 'The answer (or one of the answers) provided by the respondent to the question.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => 'Code indicating the response provided for a question.',
            'valueset' => Valuesets::QuestionnaireAnswerCodes
        ],
        'requirements' => 'Ability to retain a single-valued answer to a question.',
        'comments' => 'More complex structures (Attachment, Resource and Quantity) will typically be limited to electronic forms that can expose an appropriate user interface to capture the components and enforce the constraints of a complex data type. Additional complex types can be introduced through extensions. Must match the datatype specified by Questionnaire.item.type in the corresponding Questionnaire.',
    ];

    public const ANSWER_ITEM = [
        'definition' => 'Nested groups and/or questions found within this particular answer.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'It is useful to have "sub-questions", questions which normally appear when certain answers are given and which collect additional details.',
        'comments' => null,
    ];

    public const ITEM = [
        'definition' => 'Questions or sub-groups nested beneath a question or group.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Reports can consist of complex nested groups.',
        'comments' => null,
    ];
}
