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


    private function resourceStructure($questionnaireResponse): array
    {
        return [
            'resourceType' => 'QuestionnaireResponse',
            'id' => $this->satusehat_id,
            'identifier' => [
                'system' => $questionnaireResponse->identifier_system,
                'use' => $questionnaireResponse->identifier_use,
                'value' => $questionnaireResponse->identifier_value
            ],
            'basedOn' => $this->createReferenceArray($questionnaireResponse->based_on),
            'partOf' => $this->createReferenceArray($questionnaireResponse->part_of),
            'questionnaire' => $questionnaireResponse->questionnaire,
            'status' => $questionnaireResponse->status,
            'subject' => [
                'reference' => $questionnaireResponse->subject
            ],
            'encounter' => [
                'reference' => $questionnaireResponse->encounter
            ],
            'authored' => $this->parseDateFhir($questionnaireResponse->authored),
            'author' => [
                'reference' => $questionnaireResponse->author
            ],
            'source' => [
                'reference' => $questionnaireResponse->source
            ],
            'item' => $this->itemArray($questionnaireResponse->item)
        ];
    }


    private function itemArray($items): array
    {
        $item = [];

        if (!empty($items)) {
            foreach ($items as $i) {
                $item[] = [
                    'linkId' => $i->link_id,
                    'definition' => $i->definition,
                    'text' => $i->text,
                    'answer' => $this->answerArray($i->answer),
                    'item' => $i->item
                ];
            }
        }

        return $item;
    }


    private function answerArray($answers): array
    {
        $answer = [];

        if (!empty($answers)) {
            foreach ($answers as $a) {
                $answer[] = $this->mergeArray(
                    $a->value,
                    ['item' => $a->item]
                );
            }
        }

        return $answer;
    }
}
