<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ProcedureResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $procedure = $this->getData('procedure');

        $data = $this->resourceStructure($procedure);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($procedure): array
    {
        return [
            'resourceType' => 'Procedure',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($procedure->identifier, 'createIdentifierResource'),
            'instantiatesCanonical' => $procedure->instantiates_canonical,
            'instantiatesUri' => $procedure->instantiates_uri,
            'basedOn' => $this->createMany($procedure->basedOn, 'createReferenceResource'),
            'partOf' => $this->createMany($procedure->partOf, 'createReferenceResource'),
            'status' => $procedure->status,
            'statusReason' => $this->createCodeableConceptResource($procedure->statusReason),
            'category' => $this->createCodeableConceptResource($procedure->category),
            'code' => $this->createCodeableConceptResource($procedure->code),
            'subject' => $this->createReferenceResource($procedure->subject),
            'encounter' => $this->createReferenceResource($procedure->encounter),
            'performedDateTime' => $this->parseDateTime($procedure->performed_date_time),
            'performedPeriod' => $this->createPeriodResource($procedure->performedPeriod),
            'performedString' => $procedure->performed_string,
            'performedAge' => $this->createAgeResource($procedure->performedAge),
            'performedRange' => $this->createRangeResource($procedure->performedRange),
            'recorder' => $this->createReferenceResource($procedure->recorder),
            'asserter' => $this->createReferenceResource($procedure->asserter),
            'performer' => $this->createMany($procedure->performer, 'createPerformerResource'),
            'location' => $this->createReferenceResource($procedure->location),
            'reasonCode' => $this->createMany($procedure->reasonCode, 'createCodeableConceptResource'),
            'reasonReference' => $this->createMany($procedure->reasonReference, 'createReferenceResource'),
            'bodySite' => $this->createMany($procedure->bodySite, 'createCodeableConceptResource'),
            'outcome' => $this->createCodeableConceptResource($procedure->outcome),
            'report' => $this->createMany($procedure->report, 'createReferenceResource'),
            'complication' => $this->createMany($procedure->complication, 'createCodeableConceptResource'),
            'complicationDetail' => $this->createMany($procedure->complicationDetail, 'createReferenceResource'),
            'followUp' => $this->createMany($procedure->followUp, 'createCodeableConceptResource'),
            'note' => $this->createMany($procedure->note, 'createAnnotationResource'),
            'focalDevice' => $this->createMany($procedure->focalDevice, 'createFocalDeviceResource'),
            'usedReference' => $this->createMany($procedure->usedReference, 'createReferenceResource'),
            'usedCode' => $this->createMany($procedure->usedCode, 'createCodeableConceptResource'),
        ];
    }

    public function createPerformerResource($performer)
    {
        if (!empty($performer)) {
            return [
                'function' => $this->createCodeableConceptResource($performer->function),
                'actor' => $this->createReferenceResource($performer->actor),
                'onBehalfOf' => $this->createReferenceResource($performer->onBehalfOf),
            ];
        } else {
            return null;
        }
    }

    public function createFocalDeviceResource($focalDevice)
    {
        if (!empty($focalDevice)) {
            return [
                'action' => $this->createCodeableConceptResource($focalDevice->action),
                'manipulated' => $this->createReferenceResource($focalDevice->manipulated),
            ];
        } else {
            return null;
        }
    }
}
