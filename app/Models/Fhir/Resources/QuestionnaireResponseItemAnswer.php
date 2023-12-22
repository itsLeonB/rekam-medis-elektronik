<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireResponseItemAnswer extends FhirModel
{
    protected $table = 'question_item_answer';
    public $timestamps = false;
    protected $casts = [
        'value' => 'array',
        'item' => 'array'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireResponseItem::class, 'question_item_id');
    }

    public const VALUE = [
        'definition' => 'The answer (or one of the answers) provided by the respondent to the question.',
        'cardinality' => '0...1',
        'binding' => [
            'desc' => 'Code indicating the response provided for a question.',
            'valueset' => Valuesets::QuestionnaireAnswerCodes
        ],
        'variableTypes' => ['valueBoolean', 'valueDecimal', 'valueInteger', 'valueDate', 'valueDateTime', 'valueTime', 'valueString', 'valueUri', 'valueAttachment', 'valueCoding', 'valueQuantity', 'valueReference'],
        'requirements' => 'Ability to retain a single-valued answer to a question.',
        'comments' => 'More complex structures (Attachment, Resource and Quantity) will typically be limited to electronic forms that can expose an appropriate user interface to capture the components and enforce the constraints of a complex data type. Additional complex types can be introduced through extensions. Must match the datatype specified by Questionnaire.item.type in the corresponding Questionnaire.',
    ];

    public const ITEM = [
        'definition' => 'Nested groups and/or questions found within this particular answer.',
        'cardinality' => '0...*',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'It is useful to have "sub-questions", questions which normally appear when certain answers are given and which collect additional details.',
        'comments' => null,
    ];
}
