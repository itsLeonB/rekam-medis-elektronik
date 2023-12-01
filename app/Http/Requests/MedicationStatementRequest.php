<?php

namespace App\Http\Requests;

use App\Fhir\Dosage;
use App\Fhir\Timing;
use App\Models\MedicationStatement;
use Illuminate\Validation\Rule;

class MedicationStatementRequest extends FhirRequest
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
            $this->baseDataRules('medicationStatement.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->dosageDataRules('dosage.*.'),
        );
    }


    private function baseAttributeRules(): array
    {
        return [
            'medicationStatement' => 'required|array',
            'note' => 'nullable|array',
            'dosage' => 'nullable|array',
        ];
    }


    private function baseDataRules($prefix): array
    {
        return [
            $prefix . 'based_on' => 'nullable|array',
            $prefix . 'based_on.*' => 'required|string',
            $prefix . 'part_of' => 'nullable|array',
            $prefix . 'part_of.*' => 'required|string',
            $prefix . 'status' => ['required', Rule::in(MedicationStatement::STATUS['binding']['valueset']['code'])],
            $prefix . 'status_reason' => 'nullable|array',
            $prefix . 'status_reason.*' => 'required|string',
            $prefix . 'category' => ['nullable', Rule::in(MedicationStatement::CATEGORY['binding']['valueset']['code'])],
            $prefix . 'medication' => 'required|array',
            $prefix . 'medication.medicationCodeableConcept' => 'nullable|array',
            $prefix . 'medication.medicationCodeableConcept.text' => 'nullable|string',
            $prefix . 'medication.medicationCodeableConcept.coding' => 'nullable|array',
            $prefix . 'medication.medicationCodeableConcept.coding.*' => 'required|array',
            $prefix . 'medication.medicationCodeableConcept.coding.*.system' => 'required|string',
            $prefix . 'medication.medicationCodeableConcept.coding.*.code' => 'nullable|string',
            $prefix . 'medication.medicationCodeableConcept.coding.*.display' => 'nullable|string',
            $prefix . 'medication.medicationReference' => 'nullable|array',
            $prefix . 'medication.medicationReference.reference' => 'required|string',
            $prefix . 'subject' => 'required|string',
            $prefix . 'context' => 'nullable|string',
            $prefix . 'effective' => 'nullable|array',
            $prefix . 'effective.effectiveDateTime' => 'nullable|date',
            $prefix . 'effective.effectivePeriod' => 'nullable|array',
            $prefix . 'effective.effectivePeriod.start' => 'nullable|date',
            $prefix . 'effective.effectivePeriod.end' => 'nullable|date',
            $prefix . 'date_asserted' => 'nullable|date',
            $prefix . 'information_source' => 'nullable|string',
            $prefix . 'derived_from' => 'nullable|array',
            $prefix . 'derived_from.*' => 'required|string',
            $prefix . 'reason_code' => 'nullable|array',
            $prefix . 'reason_code.*' => 'required|string',
            $prefix . 'reason_reference' => 'nullable|array',
            $prefix . 'reason_reference.*' => 'required|string',
        ];
    }


    private function dosageDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'dosage_data' => 'required|array',
                $prefix . 'dosage_data.sequence' => 'nullable|integer',
                $prefix . 'dosage_data.text' => 'nullable|string',
                $prefix . 'dosage_data.additional_instruction' => 'nullable|array',
                $prefix . 'dosage_data.additional_instruction.*' => ['required', Rule::in(Dosage::ADDITIONAL_INSTRUCTION['binding']['valueset']['code'])],
                $prefix . 'dosage_data.patient_instruction' => 'nullable|string',
                $prefix . 'dosage_data.timing_event' => 'nullable|array',
                $prefix . 'dosage_data.timing_event.*' => 'required|date',
                $prefix . 'dosage_data.timing_repeat' => 'nullable|array',
                $prefix . 'dosage_data.timing_repeat.count' => 'nullable|integer|gte:0',
                $prefix . 'dosage_data.timing_repeat.countMax' => 'nullable|integer|gte:0',
                $prefix . 'dosage_data.timing_repeat.duration' => 'nullable|numeric',
                $prefix . 'dosage_data.timing_repeat.durationMax' => 'nullable|numeric',
                $prefix . 'dosage_data.timing_repeat.durationUnit' => ['nullable', Rule::in(Timing::REPEAT_DURATION_UNIT['binding']['valueset']['code'])],
                $prefix . 'dosage_data.timing_repeat.frequency' => 'nullable|integer|gte:0',
                $prefix . 'dosage_data.timing_repeat.frequencyMax' => 'nullable|integer|gte:0',
                $prefix . 'dosage_data.timing_repeat.period' => 'nullable|numeric',
                $prefix . 'dosage_data.timing_repeat.periodMax' => 'nullable|numeric',
                $prefix . 'dosage_data.timing_repeat.periodUnit' => ['nullable', Rule::in(Timing::REPEAT_DURATION_UNIT['binding']['valueset']['code'])],
                $prefix . 'dosage_data.timing_repeat.dayOfWeek' => 'nullable|array',
                $prefix . 'dosage_data.timing_repeat.dayOfWeek.*' => ['nullable', Rule::in(Timing::REPEAT_DAY_OF_WEEK['binding']['valueset']['code'])],
                $prefix . 'dosage_data.timing_repeat.timeOfDay' => 'nullable|array',
                $prefix . 'dosage_data.timing_repeat.timeOfDay.*' => 'nullable|date_format:H:i:s',
                $prefix . 'dosage_data.timing_repeat.when' => 'nullable|array',
                $prefix . 'dosage_data.timing_repeat.when.*' => ['nullable', Rule::in(Timing::REPEAT_WHEN['binding']['valueset']['code'])],
                $prefix . 'dosage_data.timing_repeat.offset' => 'nullable|integer|gte:0',
            ],
            $this->getDurationDataRules($prefix . 'dosage_data.timing_repeat.boundsDuration.'),
            $this->getRangeDataRules($prefix . 'dosage_data.timing_repeat.boundsRange.'),
            $this->getPeriodDataRules($prefix . 'dosage_data.timing_repeat.boundsPeriod.'),
            $this->doseRateDataRules($prefix . 'doseRate'),
        );
    }


    private function doseRateDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix => 'nullable|array',
                $prefix . '.*' => 'nullable|array',
                $prefix . '.*.type' => ['nullable', Rule::in(Dosage::DOSE_AND_RATE_TYPE['binding']['valueset']['code'])],
                $prefix . '.*.dose' => 'nullable|array',
                $prefix . '.*.rate' => 'nullable|array',
            ],
            $this->getRangeDataRules($prefix . '.*.dose.doseRange.'),
            $this->getQuantityDataRules($prefix . '.*.dose.doseQuantity.', true),
            $this->getRangeDataRules($prefix . '.*.rate.rateRange.'),
            $this->getQuantityDataRules($prefix . '.*.rate.rateQuantity.', true),
            $this->getRatioDataRules($prefix . '.*.rate.rateRatio.', true),
        );
    }
}
