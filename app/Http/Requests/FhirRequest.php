<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\CompositionSection;
use App\Models\MedicationRequestDosage;
use App\Models\MedicationRequestDosageAdditionalInstruction;
use App\Models\MedicationRequestDosageDoseRate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FhirRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if (!Auth::check()) {
        //     abort(403, 'Unauthorized action.');
        // }
        return true;
    }


    public function getCodingDataRules(string $prefix = null): array
    {
        return [
            $prefix => 'nullable|array',
            $prefix . '.system' => 'nullable|string',
            $prefix . '.version' => 'nullable|string',
            $prefix . '.code' => 'nullable|string',
            $prefix . '.display' => 'nullable|string',
            $prefix . '.userSelected' => 'nullable|boolean',
        ];
    }


    public function getAttachmentDataRules(string $prefix = null): array
    {
        return [
            $prefix => 'nullable|array',
            $prefix . '.contentType' => 'nullable|string',
            $prefix . '.language' => 'nullable|string|exists:codesystem_bcp47,code',
            $prefix . '.data' => 'nullable|string',
            $prefix . '.url' => 'nullable|string',
            $prefix . '.size' => 'nullable|integer|gte:0',
            $prefix . '.hash' => 'nullable|string',
            $prefix . '.title' => 'nullable|string',
            $prefix . '.creation' => 'nullable|datetime',
        ];
    }


    public function getNarrativeDataRules(string $prefix = null, bool $nullable = false): array
    {
        if ($nullable) {
            return [
                $prefix . 'status' => ['nullable', Rule::in(CompositionSection::TEXT_STATUS_CODE)],
                $prefix . 'div' => 'nullable|string',
            ];
        } else {
            return [
                $prefix . 'status' => ['required', Rule::in(CompositionSection::TEXT_STATUS_CODE)],
                $prefix . 'div' => 'required|string',
            ];
        }
    }

    public function getTimingDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'event' => 'nullable|array',
                $prefix . 'event.*' => 'nullable|date',
                $prefix . 'repeat' => 'nullable|array',
                $prefix . 'repeat.count' => 'nullable|integer|gte:0',
                $prefix . 'repeat.countMax' => 'nullable|integer|gte:0',
                $prefix . 'repeat.duration' => 'nullable|numeric',
                $prefix . 'repeat.durationMax' => 'nullable|numeric',
                $prefix . 'repeat.durationUnit' => ['nullable', Rule::in(Constants::PERIOD_UNIT)],
                $prefix . 'repeat.frequency' => 'nullable|integer|gte:0',
                $prefix . 'repeat.frequencyMax' => 'nullable|integer|gte:0',
                $prefix . 'repeat.period' => 'nullable|numeric',
                $prefix . 'repeat.periodMax' => 'nullable|numeric',
                $prefix . 'repeat.periodUnit' => ['nullable', Rule::in(Constants::PERIOD_UNIT)],
                $prefix . 'repeat.dayOfWeek' => 'nullable|array',
                $prefix . 'repeat.dayOfWeek.*' => ['nullable', Rule::in(Constants::DAYS_OF_WEEK)],
                $prefix . 'repeat.timeOfDay' => 'nullable|array',
                $prefix . 'repeat.timeOfDay.*' => 'nullable|date_format:H:i:s',
                $prefix . 'repeat.when' => 'nullable|array',
                $prefix . 'repeat.when.*' => ['nullable', Rule::in(Constants::TIMING_REPEAT_WHEN_CODE)],
                $prefix . 'repeat.offset' => 'nullable|integer|gte:0',
            ],
            $this->getDurationDataRules($prefix . 'repeat.boundsDuration.'),
            $this->getRangeDataRules($prefix . 'repeat.boundsRange.'),
            $this->getPeriodDataRules($prefix . 'repeat.boundsPeriod.'),
            $this->getCodeableConceptDataRules($prefix . 'code.coding.*.', Constants::TIMING_ABBREVIATION_CODE),
        );
    }

    /**
     * Get the validation rules for range data.
     *
     * @param string|null $prefix The prefix for the range data.
     * @return array The validation rules for range data.
     */
    public function getRangeDataRules(string $prefix = null): array
    {
        return array_merge(
            $this->getQuantityDataRules($prefix . 'low.', true),
            $this->getQuantityDataRules($prefix . 'high.', true),
        );
    }

    /**
     * Get the validation rules that apply to the dosage data.
     *
     * @param string|null $prefix
     * @return array
     */
    public function getDosageDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'dosage_data' => 'required|array',
                $prefix . 'additional_instruction' => 'nullable|array',
                $prefix . 'dose_rate' => 'nullable|array',
                $prefix . 'dosage_data.sequence' => 'nullable|integer',
                $prefix . 'dosage_data.text' => 'nullable|string',
                $prefix . 'dosage_data.patient_instruction' => 'nullable|string',
            ],
            $this->getTimingDataRules($prefix . 'dosage_data.timing_'),
            $this->getCodeableConceptDataRules($prefix . 'dosage_data.site_'),
            $this->getCodeableConceptDataRules($prefix . 'dosage_data.route_', Constants::DOSAGE_ROUTE_CODE),
            $this->getCodeableConceptDataRules($prefix . 'dosage_data.method_', MedicationRequestDosage::METHOD_CODE),
            $this->getRatioDataRules($prefix . 'dosage_data.max_dose_per_period_', false),
            $this->getQuantityDataRules($prefix . 'dosage_data.max_dose_per_administration_', true),
            $this->getQuantityDataRules($prefix . 'dosage_data.max_dose_per_lifetime_', true),
            $this->getCodeableConceptDataRules($prefix . 'additional_instruction.*.', MedicationRequestDosageAdditionalInstruction::CODE),
            $this->getCodeableConceptDataRules($prefix . 'dose_rate.*.', Constants::DOSERATE_TYPE_CODE),
            $this->getRangeDataRules($prefix . 'dose_rate.*.dose.doseRange.'),
            $this->getQuantityDataRules($prefix . 'dose_rate.*.dose.doseQuantity.', true),
            $this->getRatioDataRules($prefix . 'dose_rate.*.rate.rateRatio.', true),
            $this->getRangeDataRules($prefix . 'dose_rate.*.rate.rateRange.'),
            $this->getQuantityDataRules($prefix . 'dose_rate.*.rate.rateQuantity.', true),
        );
    }

    /**
     * Returns an array of validation rules for quantity data.
     *
     * @param string|null $prefix The prefix to be added to the rule keys.
     * @param bool $simple Whether to return simple or complex rules.
     * @return array The array of validation rules.
     */
    public function getQuantityDataRules(string $prefix = null, bool $simple = false): array
    {
        if ($simple) {
            return [
                $prefix . 'value' => 'nullable|numeric',
                $prefix . 'unit' => 'nullable|string',
                $prefix . 'system' => 'nullable|string',
                $prefix . 'code' => 'nullable|string',
            ];
        } else {
            return [
                $prefix . 'value' => 'nullable|numeric',
                $prefix . 'comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
                $prefix . 'unit' => 'nullable|string',
                $prefix . 'system' => 'nullable|string',
                $prefix . 'code' => 'nullable|string',
            ];
        }
    }

    /**
     * Get the validation rules that apply to the period data.
     *
     * @param  string|null  $prefix
     * @return array
     */
    public function getPeriodDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'start' => 'nullable|date',
            $prefix . 'end' => 'nullable|date',
        ];
    }

    /**
     * Get the validation rules that apply to the duration data.
     *
     * @param string|null $prefix
     * @return array
     */
    public function getDurationDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'value' => 'nullable|numeric',
            $prefix . 'comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'unit' => 'nullable|string',
            $prefix . 'system' => 'nullable|string',
            $prefix . 'code' => 'nullable|string'
        ];
    }

    /**
     * Get the validation rules that apply to the identifier data.
     *
     * @param string|null $prefix
     * @return array
     */
    public function getIdentifierDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'system' => 'required|string',
            $prefix . 'use' => ['required', Rule::in(Constants::IDENTIFIER_USE)],
            $prefix . 'value' => 'required|string',
        ];
    }

    /**
     * Get the validation rules that apply to the annotation data.
     *
     * @param  string|null  $prefix
     * @return array
     */
    public function getAnnotationDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'author' => 'nullable|array',
            $prefix . 'author.authorString' => 'nullable|string',
            $prefix . 'author.authorReference' => 'nullable|array',
            $prefix . 'author.authorReference.reference' => 'nullable|string',
            $prefix . 'time' => 'nullable|date',
            $prefix . 'text' => 'required|string',
        ];
    }

    public function getOnsetAttributeDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'onset' => 'nullable|array',
            $prefix . 'onset.onsetDateTime' => 'nullable|date',
            $prefix . 'onset.onsetAge' => 'nullable|array',
            $prefix . 'onset.onsetAge.value' => 'nullable|numeric',
            $prefix . 'onset.onsetAge.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'onset.onsetAge.unit' => 'nullable|string',
            $prefix . 'onset.onsetAge.system' => 'nullable|string',
            $prefix . 'onset.onsetAge.code' => 'nullable|string',
            $prefix . 'onset.onsetPeriod' => 'nullable|array',
            $prefix . 'onset.onsetPeriod.start' => 'nullable|date',
            $prefix . 'onset.onsetRange' => 'nullable|array',
            $prefix . 'onset.onsetRange.low' => 'nullable|array',
            $prefix . 'onset.onsetRange.low.value' => 'nullable|numeric',
            $prefix . 'onset.onsetRange.low.unit' => 'nullable|string',
            $prefix . 'onset.onsetRange.low.system' => 'nullable|string',
            $prefix . 'onset.onsetRange.low.code' => 'nullable|string',
            $prefix . 'onset.onsetRange.high' => 'nullable|array',
            $prefix . 'onset.onsetRange.high.value' => 'nullable|numeric',
            $prefix . 'onset.onsetRange.high.unit' => 'nullable|string',
            $prefix . 'onset.onsetRange.high.system' => 'nullable|string',
            $prefix . 'onset.onsetRange.high.code' => 'nullable|string',
            $prefix . 'onset.onsetString' => 'nullable|string',
        ];
    }

    public function getAbatementAttributeDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'abatement' => 'nullable|array',
            $prefix . 'abatement.abatementDateTime' => 'nullable|date',
            $prefix . 'abatement.abatementAge' => 'nullable|array',
            $prefix . 'abatement.abatementAge.value' => 'nullable|numeric',
            $prefix . 'abatement.abatementAge.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'abatement.abatementAge.unit' => 'nullable|string',
            $prefix . 'abatement.abatementAge.system' => 'nullable|string',
            $prefix . 'abatement.abatementAge.code' => 'nullable|string',
            $prefix . 'abatement.abatementPeriod' => 'nullable|array',
            $prefix . 'abatement.abatementPeriod.start' => 'nullable|date',
            $prefix . 'abatement.abatementPeriod.end' => 'nullable|date',
            $prefix . 'abatement.abatementRange' => 'nullable|array',
            $prefix . 'abatement.abatementRange.low' => 'nullable|array',
            $prefix . 'abatement.abatementRange.low.value' => 'nullable|numeric',
            $prefix . 'abatement.abatementRange.low.unit' => 'nullable|string',
            $prefix . 'abatement.abatementRange.low.system' => 'nullable|string',
            $prefix . 'abatement.abatementRange.low.code' => 'nullable|string',
            $prefix . 'abatement.abatementRange.high' => 'nullable|array',
            $prefix . 'abatement.abatementRange.high.value' => 'nullable|numeric',
            $prefix . 'abatement.abatementRange.high.unit' => 'nullable|string',
            $prefix . 'abatement.abatementRange.high.system' => 'nullable|string',
            $prefix . 'abatement.abatementRange.high.code' => 'nullable|string',
            $prefix . 'abatement.abatementString' => 'nullable|string',
        ];
    }

    public function getCodeableConceptDataRules(string $prefix = null, array $code = null): array
    {
        if ($code) {
            return [
                $prefix . 'system' => 'nullable|string',
                $prefix . 'code' => ['required', Rule::in($code)],
                $prefix . 'display' => 'nullable|string',
            ];
        } else {
            return [
                $prefix . 'system' => 'nullable|string',
                $prefix . 'code' => 'required|string',
                $prefix . 'display' => 'nullable|string',
            ];
        }
    }

    public function getEffectiveDataRules($prefix = null): array
    {
        return [
            $prefix . 'effective' => 'nullable|array',
            $prefix . 'effective.effectiveDateTime' => 'nullable|date',
            $prefix . 'effective.effectivePeriod' => 'nullable|array',
            $prefix . 'effective.effectivePeriod.start' => 'nullable|date',
            $prefix . 'effective.effectivePeriod.end' => 'nullable|date',
            $prefix . 'effective.effectiveTiming' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.event' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.event.*' => 'nullable|date',
            $prefix . 'effective.effectiveTiming.repeat' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration.value' => 'nullable|integer',
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration.comparator' => 'nullable|string|max:2',
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration.unit' => 'nullable|string',
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration.system' => 'nullable|string',
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration.code' => 'nullable|string',
            $prefix . 'effective.effectiveTiming.repeat.count' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.repeat.countMax' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.repeat.duration' => 'nullable|numeric',
            $prefix . 'effective.effectiveTiming.repeat.durationMax' => 'nullable|numeric',
            $prefix . 'effective.effectiveTiming.repeat.frequency' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.repeat.frequencyMax' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.repeat.period' => 'nullable|numeric',
            $prefix . 'effective.effectiveTiming.repeat.periodMax' => 'nullable|numeric',
            $prefix . 'effective.effectiveTiming.repeat.periodUnit' => ['nullable', Rule::in(Constants::PERIOD_UNIT)],
            $prefix . 'effective.effectiveTiming.repeat.dayOfWeek' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.repeat.dayOfWeek.*' => ['nullable', Rule::in(Constants::DAYS_OF_WEEK)],
            $prefix . 'effective.effectiveTiming.repeat.timeOfDay' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.repeat.timeOfDay.*' => 'nullable|date_format:H:i:s',
            $prefix . 'effective.effectiveTiming.repeat.when' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.repeat.when.*' => ['nullable', Rule::in(Constants::TIMING_REPEAT_WHEN_CODE)],
            $prefix . 'effective.effectiveTiming.repeat.offset' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.code' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.code.coding' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.code.coding.*.system' => 'nullable|string',
            $prefix . 'effective.effectiveTiming.code.coding.*.code' => ['nullable', Rule::in(Constants::TIMING_ABBREVIATION_CODE)],
            $prefix . 'effective.effectiveTiming.code.coding.*.display' => 'nullable|string',
            $prefix . 'effective.effectiveTiming.code.text' => 'nullable|string',
            $prefix . 'effective.effectiveInstant' => 'nullable|date',
        ];
    }

    public function getValueDataRules($prefix = null): array
    {
        return [
            $prefix . 'value' => 'nullable|array',
            $prefix . 'value.quantity' => 'nullable|array',
            $prefix . 'value.quantity.value' => 'nullable|numeric',
            $prefix . 'value.quantity.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.quantity.unit' => 'nullable|string',
            $prefix . 'value.quantity.system' => 'nullable|string',
            $prefix . 'value.quantity.code' => 'nullable|string',
            $prefix . 'value.valueCodeableConcept' => 'nullable|array',
            $prefix . 'value.valueCodeableConcept.coding' => 'nullable|array',
            $prefix . 'value.valueCodeableConcept.coding.*.system' => 'nullable|string',
            $prefix . 'value.valueCodeableConcept.coding.*.code' => 'nullable|string',
            $prefix . 'value.valueCodeableConcept.coding.*.display' => 'nullable|string',
            $prefix . 'value.valueCodeableConcept.text' => 'nullable|string',
            $prefix . 'value.valueString' => 'nullable|string',
            $prefix . 'value.valueBoolean' => 'nullable|boolean',
            $prefix . 'value.valueInteger' => 'nullable|integer',
            $prefix . 'value.valueRange' => 'nullable|array',
            $prefix . 'value.valueRange.low' => 'nullable|array',
            $prefix . 'value.valueRange.low.value' => 'nullable|numeric',
            $prefix . 'value.valueRange.low.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.valueRange.low.unit' => 'nullable|string',
            $prefix . 'value.valueRange.low.system' => 'nullable|string',
            $prefix . 'value.valueRange.low.code' => 'nullable|string',
            $prefix . 'value.valueRange.high' => 'nullable|array',
            $prefix . 'value.valueRange.high.value' => 'nullable|numeric',
            $prefix . 'value.valueRange.high.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.valueRange.high.unit' => 'nullable|string',
            $prefix . 'value.valueRange.high.system' => 'nullable|string',
            $prefix . 'value.valueRange.high.code' => 'nullable|string',
            $prefix . 'value.valueRatio' => 'nullable|array',
            $prefix . 'value.valueRatio.numerator' => 'nullable|array',
            $prefix . 'value.valueRatio.numerator.value' => 'nullable|numeric',
            $prefix . 'value.valueRatio.numerator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.valueRatio.numerator.unit' => 'nullable|string',
            $prefix . 'value.valueRatio.numerator.system' => 'nullable|string',
            $prefix . 'value.valueRatio.numerator.code' => 'nullable|string',
            $prefix . 'value.valueRatio.denominator' => 'nullable|array',
            $prefix . 'value.valueRatio.denominator.value' => 'nullable|numeric',
            $prefix . 'value.valueRatio.denominator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.valueRatio.denominator.unit' => 'nullable|string',
            $prefix . 'value.valueRatio.denominator.system' => 'nullable|string',
            $prefix . 'value.valueRatio.denominator.code' => 'nullable|string',
            $prefix . 'value.valueSampledData' => 'nullable|array',
            $prefix . 'value.valueSampledData.origin' => 'nullable|array',
            $prefix . 'value.valueSampledData.origin.value' => 'nullable|numeric',
            $prefix . 'value.valueSampledData.origin.unit' => 'nullable|string',
            $prefix . 'value.valueSampledData.origin.system' => 'nullable|string',
            $prefix . 'value.valueSampledData.origin.code' => 'nullable|string',
            $prefix . 'value.valueSampledData.period' => 'nullable|numeric',
            $prefix . 'value.valueSampledData.factor' => 'nullable|numeric',
            $prefix . 'value.valueSampledData.lowerLimit' => 'nullable|numeric',
            $prefix . 'value.valueSampledData.upperLimit' => 'nullable|numeric',
            $prefix . 'value.valueSampledData.dimensions' => 'nullable|integer|gte:0',
            $prefix . 'value.valueSampledData.data' => 'nullable|string',
            $prefix . 'value.valueTime' => 'nullable|date_format:H:i:s',
            $prefix . 'value.valueDateTime' => 'nullable|date',
            $prefix . 'value.valuePeriod' => 'nullable|array',
            $prefix . 'value.valuePeriod.start' => 'nullable|date',
            $prefix . 'value.valuePeriod.end' => 'nullable|date',
        ];
    }

    public function getReferenceDataRules($prefix = null, bool $nullable = false): array
    {
        if ($nullable) {
            return [
                $prefix . 'reference' => 'nullable|string',
            ];
        } else {
            return [
                $prefix . 'reference' => 'required|string',
            ];
        }
    }

    public function getTelecomDataRules($prefix = null): array
    {
        return [
            $prefix . 'system' => ['required', 'string', Rule::in(Constants::TELECOM_SYSTEM_CODE)],
            $prefix . 'use' => ['required', 'string', Rule::in(Constants::TELECOM_USE_CODE)],
            $prefix . 'value' => 'required|string|max:255',
        ];
    }

    public function getAddressDataRules($prefix = null): array
    {
        return [
            $prefix . 'use' => ['nullable', 'string', Rule::in(Constants::ADDRESS_USE_CODE)],
            $prefix . 'type' => ['nullable', 'string', Rule::in(Constants::ADDRESS_TYPE_CODE)],
            $prefix . 'line' => 'nullable|array',
            $prefix . 'line.*' => 'nullable|string',
            $prefix . 'country' => 'nullable|string|max:255',
            $prefix . 'postal_code' => 'nullable|string|max:255',
            $prefix . 'province' => 'nullable|integer|gte:0|digits:2|exists:codesystem_administrativecode,kode',
            $prefix . 'city' => 'nullable|integer|gte:0|digits:4|exists:codesystem_administrativecode,kode',
            $prefix . 'district' => 'nullable|integer|gte:0|digits:6|exists:codesystem_administrativecode,kode',
            $prefix . 'village' => 'nullable|integer|gte:0|digits:10|exists:codesystem_administrativecode,kode',
            $prefix . 'rt' => 'nullable|integer|gte:0|max_digits:2',
            $prefix . 'rw' => 'nullable|integer|gte:0|max_digits:2',
        ];
    }

    public function getPerformedDataRules($prefix = null): array
    {
        return [
            $prefix . 'performed' => 'nullable|array',
            $prefix . 'performed.performedDateTime' => 'nullable|date',
            $prefix . 'performed.performedPeriod' => 'nullable|array',
            $prefix . 'performed.performedPeriod.start' => 'nullable|date',
            $prefix . 'performed.performedPeriod.end' => 'nullable|date',
            $prefix . 'performed.performedString' => 'nullable|string',
            $prefix . 'performed.performedAge' => 'nullable|array',
            $prefix . 'performed.performedAge.value' => 'nullable|numeric',
            $prefix . 'performed.performedAge.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'performed.performedAge.unit' => 'nullable|string',
            $prefix . 'performed.performedAge.system' => 'nullable|string',
            $prefix . 'performed.performedAge.code' => 'nullable|string',
            $prefix . 'performed.performedRange' => 'nullable|array',
            $prefix . 'performed.performedRange.low' => 'nullable|array',
            $prefix . 'performed.performedRange.low.value' => 'nullable|numeric',
            $prefix . 'performed.performedRange.low.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'performed.performedRange.low.unit' => 'nullable|string',
            $prefix . 'performed.performedRange.low.system' => 'nullable|string',
            $prefix . 'performed.performedRange.low.code' => 'nullable|string',
            $prefix . 'performed.performedRange.high' => 'nullable|array',
            $prefix . 'performed.performedRange.high.value' => 'nullable|numeric',
            $prefix . 'performed.performedRange.high.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'performed.performedRange.high.unit' => 'nullable|string',
            $prefix . 'performed.performedRange.high.system' => 'nullable|string',
            $prefix . 'performed.performedRange.high.code' => 'nullable|string',
        ];
    }

    public function getRatioDataRules(string $prefix = null, bool $isArray = false): array
    {
        if ($isArray) {
            return [
                $prefix . 'numerator.value' => 'nullable|numeric',
                $prefix . 'numerator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
                $prefix . 'numerator.unit' => 'nullable|string',
                $prefix . 'numerator.system' => 'nullable|string',
                $prefix . 'numerator.code' => 'nullable|string',
                $prefix . 'denominator.value' => 'nullable|numeric',
                $prefix . 'denominator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
                $prefix . 'denominator.unit' => 'nullable|string',
                $prefix . 'denominator.system' => 'nullable|string',
                $prefix . 'denominator.code' => 'nullable|string',
            ];
        } else {
            return [
                $prefix . 'numerator_value' => 'nullable|numeric',
                $prefix . 'numerator_comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
                $prefix . 'numerator_unit' => 'nullable|string',
                $prefix . 'numerator_system' => 'nullable|string',
                $prefix . 'numerator_code' => 'nullable|string',
                $prefix . 'denominator_value' => 'nullable|numeric',
                $prefix . 'denominator_comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
                $prefix . 'denominator_unit' => 'nullable|string',
                $prefix . 'denominator_system' => 'nullable|string',
                $prefix . 'denominator_code' => 'nullable|string',
            ];
        }
    }
}
