<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CompositionResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $composition = $this->getData('composition');

        $data = $this->resourceStructure($composition);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($composition): array
    {
        return [
            'resourceType' => 'Composition',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierResource($composition->identifier),
            'status' => $composition->status,
            'type' => $this->createCodeableConceptResource($composition->type),
            'category' => $this->createMany($composition->category, 'createCodeableConceptResource'),
            'subject' => $this->createReferenceResource($composition->subject),
            'encounter' => $this->createReferenceResource($composition->encounter),
            'date' => $this->parseDateTime($composition->date),
            'author' => $this->createMany($composition->author, 'createReferenceResource'),
            'title' => $composition->title,
            'confidentiality' => $composition->confidentiality,
            'attester' => $this->createMany($composition->attester, 'createAttesterResource'),
            'custodian' => $this->createReferenceResource($composition->custodian),
            'relatesTo' => $this->createMany($composition->relatesTo, 'createRelatesToResource'),
            'event' => $this->createMany($composition->event, 'createEventResource'),
            'section' => $this->createMany($composition->section, 'createSectionResource'),
            'extension' => [
                $this->createComplexExtensionResource($composition->documentStatus)
            ]
        ];
    }

    public function createAttesterResource($attester)
    {
        if (!empty($attester)) {
            return [
                'mode' => $attester->mode,
                'time' => $this->parseDateTime($attester->time),
                'party' => $this->createReferenceResource($attester->party)
            ];
        } else {
            return null;
        }
    }

    public function createRelatesToResource($relatesTo)
    {
        if (!empty($relatesTo)) {
            return [
                'code' => $relatesTo->code,
                'targetIdentifier' => $this->createIdentifierResource($relatesTo->targetIdentifier),
                'targetReference' => $this->createReferenceResource($relatesTo->targetReference)
            ];
        } else {
            return null;
        }
    }

    public function createEventResource($event)
    {
        if (!empty($event)) {
            return [
                'code' => $this->createMany($event->code, 'createCodeableConceptResource'),
                'period' => $this->createPeriodResource($event->period),
                'detail' => $this->createMany($event->detail, 'createReferenceResource')
            ];
        } else {
            return null;
        }
    }

    public function createSectionResource($section)
    {
        if (!empty($section)) {
            return [
                'title' => $section->title,
                'code' => $this->createCodeableConceptResource($section->code),
                'author' => $this->createMany($section->author, 'createReferenceResource'),
                'focus' => $this->createReferenceResource($section->focus),
                'text' => $this->createNarrativeResource($section->text),
                'mode' => $section->mode,
                'orderedBy' => $this->createCodeableConceptResource($section->orderedBy),
                'entry' => $this->createMany($section->entry, 'createReferenceResource'),
                'emptyReason' => $this->createCodeableConceptResource($section->emptyReason),
                'section' => $this->createMany($section->section, 'createSectionResource')
            ];
        } else {
            return null;
        }
    }
}
