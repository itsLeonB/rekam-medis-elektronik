<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class QuestionnaireResponseResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $questionnaireResponse = $this->getData('questionnaireResponse');
        $data = $this->resourceStructure($questionnaireResponse);
        $data = $this->removeEmptyValues($data);
        return $data;
    }


    public function resourceStructure($questionnaireResponse): array
    {
        return [
            'resourceType' => 'QuestionnaireResponse',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierResource($questionnaireResponse->identifier),
            'basedOn' => $this->createMany($questionnaireResponse->basedOn, 'createReferenceResource'),
            'partOf' => $this->createMany($questionnaireResponse->partOf, 'createReferenceResource'),
            'questionnaire' => $questionnaireResponse->questionnaire,
            'status' => $questionnaireResponse->status,
            'subject' => $this->createReferenceResource($questionnaireResponse->subject),
            'encounter' => $this->createReferenceResource($questionnaireResponse->encounter),
            'authored' => $this->parseDateTime($questionnaireResponse->authored),
            'author' => $this->createReferenceResource($questionnaireResponse->author),
            'source' => $this->createReferenceResource($questionnaireResponse->source),
            'item' => $this->createMany($questionnaireResponse->item, 'createItemResource'),
        ];
    }

    public function createItemResource($item)
    {
        if (!empty($item)) {
            return [
                'linkId' => $item->link_id,
                'definition' => $item->definition,
                'text' => $item->text,
                'answer' => $this->createMany($item->answer, 'createAnswerResource'),
                'item' => $this->createMany($item->item, 'createItemResource'),
            ];
        } else {
            return null;
        }
    }

    public function createAnswerResource($answer)
    {
        if (!empty($answer)) {
            return [
                'valueBoolean' => $answer->value_boolean,
                'valueDecimal' => $answer->value_decimal,
                'valueInteger' => $answer->value_integer,
                'valueDate' => $this->parseDate($answer->value_date),
                'valueDateTime' => $this->parseDateTime($answer->value_datetime),
                'valueTime' => $this->parseTime($answer->value_time),
                'valueString' => $answer->value_string,
                'valueUri' => $answer->value_uri,
                'valueAttachment' => $this->createAttachmentResource($answer->valueAttachment),
                'valueCoding' => $this->createCodingResource($answer->valueCoding),
                'valueQuantity' => $this->createQuantityResource($answer->valueQuantity),
                'valueReference' => $this->createReferenceResource($answer->valueReference),
                'item' => $this->createMany($answer->item, 'createItemResource'),
            ];
        } else {
            return null;
        }
    }
}
