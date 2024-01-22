<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\Attachment;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\Quantity;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class QuestionnaireResponseItemAnswer extends FhirModel
{
    use HasFactory;

    protected $table = 'question_item_answer';

    public $timestamps = false;

    protected $casts = [
        'value_boolean' => 'boolean',
        'value_decimal' => 'float',
        'value_date' => 'date',
        'value_date_time' => 'datetime',
        'value_time' => 'datetime'
    ];

    public function parentItem(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireResponseItem::class, 'question_item_id');
    }

    public function valueAttachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function valueCoding(): MorphOne
    {
        return $this->morphOne(Coding::class, 'codeable');
    }

    public function valueQuantity(): MorphOne
    {
        return $this->morphOne(Quantity::class, 'quantifiable');
    }

    public function valueReference(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public function item(): HasMany
    {
        return $this->hasMany(QuestionnaireResponseItem::class, 'parent_id');
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
