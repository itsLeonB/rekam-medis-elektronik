<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ObservationResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $observation = $this->getData('observation');

        $data = $this->resourceStructure($observation);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($observation): array
    {
        return [
            'resourceType' => 'Observation',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($observation->identifier, 'createIdentifierResource'),
            'basedOn' => $this->createMany($observation->basedOn, 'createReferenceResource'),
            'partOf' => $this->createMany($observation->partOf, 'createReferenceResource'),
            'status' => $observation->status,
            'category' => $this->createMany($observation->category, 'createCodeableConceptResource'),
            'code' => $this->createCodeableConceptResource($observation->code),
            'subject' => $this->createReferenceResource($observation->subject),
            'focus' => $this->createMany($observation->focus, 'createReferenceResource'),
            'encounter' => $this->createReferenceResource($observation->encounter),
            'effectiveDateTime' => $this->parseDateTime($observation->effective_date_time),
            'effectivePeriod' => $this->createPeriodResource($observation->effectivePeriod),
            'effectiveTiming' => $this->createTimingResource($observation->effectiveTiming),
            'effectiveInstant' => $this->parseDateTime($observation->effective_instant),
            'issued' => $this->parseDateTime($observation->issued),
            'performer' => $this->createMany($observation->performer, 'createReferenceResource'),
            'valueQuantity' => $this->createQuantityResource($observation->valueQuantity),
            'valueCodeableConcept' => $this->createCodeableConceptResource($observation->valueCodeableConcept),
            'valueString' => $observation->value_string,
            'valueBoolean' => $observation->value_boolean,
            'valueInteger' => $observation->value_integer,
            'valueRange' => $this->createRangeResource($observation->valueRange),
            'valueRatio' => $this->createRatioResource($observation->valueRatio),
            'valueSampledData' => $this->createSampledDataResource($observation->valueSampledData),
            'valueTime' => $this->parseTime($observation->value_time),
            'valueDateTime' => $this->parseDateTime($observation->value_date_time),
            'valuePeriod' => $this->createPeriodResource($observation->valuePeriod),
            'dataAbsentReason' => $this->createCodeableConceptResource($observation->dataAbsentReason),
            'interpretation' => $this->createMany($observation->interpretation, 'createCodeableConceptResource'),
            'note' => $this->createMany($observation->note, 'createAnnotationResource'),
            'bodySite' => $this->createCodeableConceptResource($observation->bodySite),
            'method' => $this->createCodeableConceptResource($observation->method),
            'specimen' => $this->createReferenceResource($observation->specimen),
            'device' => $this->createReferenceResource($observation->device),
            'referenceRange' => $this->createMany($observation->referenceRange, 'createReferenceRangeResource'),
            'hasMember' => $this->createMany($observation->hasMember, 'createReferenceResource'),
            'derivedFrom' => $this->createMany($observation->derivedFrom, 'createReferenceResource'),
            'component' => $this->createMany($observation->component, 'createComponentResource'),
        ];
    }

    public function createReferenceRangeResource($refRange)
    {
        if (!empty($refRange)) {
            return [
                'low' => $this->createSimpleQuantityResource($refRange->low),
                'high' => $this->createSimpleQuantityResource($refRange->high),
                'type' => $this->createCodeableConceptResource($refRange->type),
                'appliesTo' => $this->createMany($refRange->appliesTo, 'createCodeableConceptResource'),
                'age' => $this->createRangeResource($refRange->age),
                'text' => $refRange->text,
            ];
        } else {
            return null;
        }
    }

    public function createComponentResource($component)
    {
        if (!empty($component)) {
            return [
                'code' => $this->createCodeableConceptResource($component->code),
                'valueQuantity' => $this->createQuantityResource($component->valueQuantity),
                'valueCodeableConcept' => $this->createCodeableConceptResource($component->valueCodeableConcept),
                'valueString' => $component->value_string,
                'valueBoolean' => $component->value_boolean,
                'valueInteger' => $component->value_integer,
                'valueRange' => $this->createRangeResource($component->valueRange),
                'valueRatio' => $this->createRatioResource($component->valueRatio),
                'valueSampledData' => $this->createSampledDataResource($component->valueSampledData),
                'valueTime' => $this->parseTime($component->value_time),
                'valueDateTime' => $this->parseDateTime($component->value_date_time),
                'valuePeriod' => $this->createPeriodResource($component->valuePeriod),
                'dataAbsentReason' => $this->createCodeableConceptResource($component->dataAbsentReason),
                'interpretation' => $this->createMany($component->interpretation, 'createCodeableConceptResource'),
                'referenceRange' => $this->createMany($component->referenceRange, 'createReferenceRangeResource'),
            ];
        } else {
            return null;
        }
    }
}
