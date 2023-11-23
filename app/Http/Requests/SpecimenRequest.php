<?php

namespace App\Http\Requests;

use App\Models\Specimen;
use App\Models\SpecimenCondition;
use App\Models\SpecimenContainer;
use App\Models\SpecimenProcessing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecimenRequest extends FhirRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(
            $this->baseAttributeRules(),
            $this->baseDataRules('specimen.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getReferenceDataRules('parent.*.'),
            $this->getReferenceDataRules('request.*.'),
            $this->processingDataRules('processing.*.'),
            $this->containerDataRules('container.*.'),
            $this->getCodeableConceptDataRules('condition.*.', SpecimenCondition::CODE),
            $this->getAnnotationDataRules('note.*.')
        );
    }


    private function baseAttributeRules(): array
    {
        return [
            'specimen' => 'required|array',
            'identifier' => 'nullable|array',
            'parent' => 'nullable|array',
            'request' => 'nullable|array',
            'processing' => 'nullable|array',
            'container' => 'nullable|array',
            'condition' => 'nullable|array',
            'note' => 'nullable|array'
        ];
    }


    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'accession_identifier_system' => 'nullable|string',
                $prefix . 'accession_identifier_use' => 'nullable|string',
                $prefix . 'accession_identifier_value' => 'nullable|string',
                $prefix . 'status' => ['required', Rule::in(Specimen::STATUS_CODE)],
                $prefix . 'type_system' => 'required|string',
                $prefix . 'type_code' => 'required|string|exists:valueset_specimen_type,code',
                $prefix . 'type_display' => 'required|string',
                $prefix . 'subject' => 'required|string',
                $prefix . 'received_time' => 'nullable|string',
                $prefix . 'collection_collector' => 'nullable|string',
                $prefix . 'collection_collected' => 'nullable|array',
                $prefix . 'collection_collected.collectedDateTime' => 'nullable|date',
                $prefix . 'collection_collected.collectedPeriod' => 'nullable|array',
                $prefix . 'collection_collected.collectedPeriod.start' => 'required|date',
                $prefix . 'collection_collected.collectedPeriod.end' => 'nullable|date',
                $prefix . 'collection_method' => ['nullable', Rule::in(Specimen::COLLECTION_METHOD_CODE)],
                $prefix . 'collection_fasting_status' => 'nullable|array',
            ],
            $this->getDurationDataRules($prefix . 'collection_duration_'),
            $this->getQuantityDataRules($prefix . 'collection_quantity_'),
            $this->getCodeableConceptDataRules($prefix . 'collection_body_site_'),
            $this->getCodeableConceptDataRules($prefix . 'collection_fasting_status.fastingStatusCodeableConcept.coding.*.', Specimen::COLLECTION_FASTING_STATUS_CODE),
            $this->getDurationDataRules($prefix . 'collection_fasting_status.fastingStatusDuration.'),
        );
    }


    private function processingDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'description' => 'nullable|string',
                $prefix . 'procedure' => ['nullable', Rule::in(SpecimenProcessing::PROCEDURE_CODE)],
                $prefix . 'additive' => 'nullable|array',
                $prefix . 'additive.*' => 'nullable|string',
                $prefix . 'time' => 'nullable|array',
                $prefix . 'time.timeDateTime' => 'nullable|date',
                $prefix . 'time.timePeriod' => 'nullable|array',
                $prefix . 'time.timePeriod.start' => 'required|date',
                $prefix . 'time.timePeriod.end' => 'nullable|date',
            ],
        );
    }


    private function containerDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'container_data' => 'required|array',
                $prefix . 'container_data.description' => 'nullable|string',
                $prefix . 'container_data.type' => 'nullable|string|exists:valueset_specimen_containertype,code',
                $prefix . 'container_data.additive' => 'nullable|array',
                $prefix . 'container_data.additive.additiveCodeableConcept' => 'nullable|array',
                $prefix . 'container_data.additive.additiveCodeableConcept.coding' => 'nullable|array',
                $prefix . 'container_data.additive.additiveReference' => 'nullable|array',
            ],
            $this->getQuantityDataRules($prefix . 'container_data.capacity_', true),
            $this->getQuantityDataRules($prefix . 'container_data.specimen_quantity_', true),
            $this->getCodeableConceptDataRules($prefix . 'container_data.additive.additiveCodeableConcept.coding.*.', SpecimenContainer::ADDITIVE_CODE),
            $this->getReferenceDataRules($prefix . 'container_data.additive.additiveReference.'),
            $this->getIdentifierDataRules($prefix . 'identifier.*.')
        );
    }
}
