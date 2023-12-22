<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionnaireResponseItem extends FhirModel
{
    protected $table = 'questionnaire_response_item';
    public $timestamps = false;
    protected $casts = ['item' => 'array'];

    public function questionnaireResponse(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireResponse::class, 'questionnaire_id');
    }

    public function answer(): HasMany
    {
        return $this->hasMany(QuestionnaireResponseItemAnswer::class, 'question_item_id');
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
