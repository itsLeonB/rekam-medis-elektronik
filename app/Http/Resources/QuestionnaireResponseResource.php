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
        $data = removeEmptyValues($data);
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
            'basedOn' => $this->referenceArray($questionnaireResponse->based_on),
            'partOf' => $this->referenceArray($questionnaireResponse->part_of),
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
                    'answer' => $i->answer,
                    'item' => $i->item
                ];
            }
        }

        return $item;
    }


    private function referenceArray($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = [
                    'reference' => $r
                ];
            }
        }

        return $reference;
    }
}
