<?php

namespace App\Http\Requests;

use App\Constants;
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
            $prefix . 'onset.onsetAge.value' => 'nullable|decimal',
            $prefix . 'onset.onsetAge.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'onset.onsetAge.unit' => 'nullable|string',
            $prefix . 'onset.onsetAge.system' => 'nullable|string',
            $prefix . 'onset.onsetAge.code' => 'nullable|string',
            $prefix . 'onset.onsetPeriod' => 'nullable|array',
            $prefix . 'onset.onsetPeriod.start' => 'nullable|date',
            $prefix . 'onset.onsetRange' => 'nullable|array',
            $prefix . 'onset.onsetRange.low' => 'nullable|array',
            $prefix . 'onset.onsetRange.low.value' => 'nullable|decimal',
            $prefix . 'onset.onsetRange.low.unit' => 'nullable|string',
            $prefix . 'onset.onsetRange.low.system' => 'nullable|string',
            $prefix . 'onset.onsetRange.low.code' => 'nullable|string',
            $prefix . 'onset.onsetRange.high' => 'nullable|array',
            $prefix . 'onset.onsetRange.high.value' => 'nullable|decimal',
            $prefix . 'onset.onsetRange.high.unit' => 'nullable|string',
            $prefix . 'onset.onsetRange.high.system' => 'nullable|string',
            $prefix . 'onset.onsetRange.high.code' => 'nullable|string',
            $prefix . 'onset.onsetString' => 'nullable|string',
        ];
    }

    public function getAbatementAttributeDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'abatement.abatementDateTime' => 'nullable|date',
            $prefix . 'abatement.abatementAge' => 'nullable|array',
            $prefix . 'abatement.abatementAge.value' => 'nullable|decimal',
            $prefix . 'abatement.abatementAge.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'abatement.abatementAge.unit' => 'nullable|string',
            $prefix . 'abatement.abatementAge.system' => 'nullable|string',
            $prefix . 'abatement.abatementAge.code' => 'nullable|string',
            $prefix . 'abatement.abatementPeriod' => 'nullable|array',
            $prefix . 'abatement.abatementPeriod.start' => 'nullable|date',
            $prefix . 'abatement.abatementPeriod.end' => 'nullable|date',
            $prefix . 'abatement.abatementRange' => 'nullable|array',
            $prefix . 'abatement.abatementRange.low' => 'nullable|array',
            $prefix . 'abatement.abatementRange.low.value' => 'nullable|decimal',
            $prefix . 'abatement.abatementRange.low.unit' => 'nullable|string',
            $prefix . 'abatement.abatementRange.low.system' => 'nullable|string',
            $prefix . 'abatement.abatementRange.low.code' => 'nullable|string',
            $prefix . 'abatement.abatementRange.high' => 'nullable|array',
            $prefix . 'abatement.abatementRange.high.value' => 'nullable|decimal',
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
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration.unit' => 'nullable|string|max:2',
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration.system' => 'nullable|string',
            $prefix . 'effective.effectiveTiming.repeat.boundsDuration.code' => 'nullable|string',
            $prefix . 'effective.effectiveTiming.repeat.count' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.repeat.countMax' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.repeat.duration' => 'nullable|decimal',
            $prefix . 'effective.effectiveTiming.repeat.durationMax' => 'nullable|decimal',
            $prefix . 'effective.effectiveTiming.repeat.frequency' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.repeat.frequencyMax' => 'nullable|integer|gte:0',
            $prefix . 'effective.effectiveTiming.repeat.period' => 'nullable|decimal',
            $prefix . 'effective.effectiveTiming.repeat.periodMax' => 'nullable|decimal',
            $prefix . 'effective.effectiveTiming.repeat.periodUnit' => ['nullable', Rule::in(Constants::PERIOD_UNIT)],
            $prefix . 'effective.effectiveTiming.repeat.dayOfWeek' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.repeat.dayOfWeek.*' => ['nullable', Rule::in(Constants::DAY_OF_WEEK)],
            $prefix . 'effective.effectiveTiming.repeat.timeOfDay' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.repeat.timeOfDay.*' => 'nullable|date_format:H:i:s',
            $prefix . 'effective.effectiveTiming.repeat.when' => 'nullable|array',
            $prefix . 'effective.effectiveTiming.repeat.when.*' => ['nullable', Rule::in(Constants::TIMING_EVENT_CODE)],
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
            $prefix . 'value.quantity.value' => 'nullable|decimal',
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
            $prefix . 'value.valueRange.low.value' => 'nullable|decimal',
            $prefix . 'value.valueRange.low.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.valueRange.low.unit' => 'nullable|string',
            $prefix . 'value.valueRange.low.system' => 'nullable|string',
            $prefix . 'value.valueRange.low.code' => 'nullable|string',
            $prefix . 'value.valueRange.high' => 'nullable|array',
            $prefix . 'value.valueRange.high.value' => 'nullable|decimal',
            $prefix . 'value.valueRange.high.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.valueRange.high.unit' => 'nullable|string',
            $prefix . 'value.valueRange.high.system' => 'nullable|string',
            $prefix . 'value.valueRange.high.code' => 'nullable|string',
            $prefix . 'value.valueRatio' => 'nullable|array',
            $prefix . 'value.valueRatio.numerator' => 'nullable|array',
            $prefix . 'value.valueRatio.numerator.value' => 'nullable|decimal',
            $prefix . 'value.valueRatio.numerator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.valueRatio.numerator.unit' => 'nullable|string',
            $prefix . 'value.valueRatio.numerator.system' => 'nullable|string',
            $prefix . 'value.valueRatio.numerator.code' => 'nullable|string',
            $prefix . 'value.valueRatio.denominator' => 'nullable|array',
            $prefix . 'value.valueRatio.denominator.value' => 'nullable|decimal',
            $prefix . 'value.valueRatio.denominator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'value.valueRatio.denominator.unit' => 'nullable|string',
            $prefix . 'value.valueRatio.denominator.system' => 'nullable|string',
            $prefix . 'value.valueRatio.denominator.code' => 'nullable|string',
            $prefix . 'value.valueSampledData' => 'nullable|array',
            $prefix . 'value.valueSampledData.origin' => 'nullable|array',
            $prefix . 'value.valueSampledData.origin.value' => 'nullable|decimal',
            $prefix . 'value.valueSampledData.origin.unit' => 'nullable|string',
            $prefix . 'value.valueSampledData.origin.system' => 'nullable|string',
            $prefix . 'value.valueSampledData.origin.code' => 'nullable|string',
            $prefix . 'value.valueSampledData.period' => 'nullable|decimal',
            $prefix . 'value.valueSampledData.factor' => 'nullable|decimal',
            $prefix . 'value.valueSampledData.lowerLimit' => 'nullable|decimal',
            $prefix . 'value.valueSampledData.upperLimit' => 'nullable|decimal',
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
            $prefix . 'use' => ['required', 'string', Rule::in(Constants::ADDRESS_USE_CODE)],
            $prefix . 'line' => 'required|string',
            $prefix . 'country' => 'required|string|max:255',
            $prefix . 'postal_code' => 'required|string|max:255',
            $prefix . 'province' => 'required|integer|gte:0|digits:2',
            $prefix . 'city' => 'required|integer|gte:0|digits:4',
            $prefix . 'district' => 'required|integer|gte:0|digits:6',
            $prefix . 'village' => 'required|integer|gte:0|digits:10',
            $prefix . 'rt' => 'required|integer|gte:0|max_digits:2',
            $prefix . 'rw' => 'required|integer|gte:0|max_digits:2',
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
            $prefix . 'performed.performedAge.value' => 'nullable|decimal',
            $prefix . 'performed.performedAge.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'performed.performedAge.unit' => 'nullable|string',
            $prefix . 'performed.performedAge.system' => 'nullable|string',
            $prefix . 'performed.performedAge.code' => 'nullable|string',
            $prefix . 'performed.performedRange' => 'nullable|array',
            $prefix . 'performed.performedRange.low' => 'nullable|array',
            $prefix . 'performed.performedRange.low.value' => 'nullable|decimal',
            $prefix . 'performed.performedRange.low.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'performed.performedRange.low.unit' => 'nullable|string',
            $prefix . 'performed.performedRange.low.system' => 'nullable|string',
            $prefix . 'performed.performedRange.low.code' => 'nullable|string',
            $prefix . 'performed.performedRange.high' => 'nullable|array',
            $prefix . 'performed.performedRange.high.value' => 'nullable|decimal',
            $prefix . 'performed.performedRange.high.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            $prefix . 'performed.performedRange.high.unit' => 'nullable|string',
            $prefix . 'performed.performedRange.high.system' => 'nullable|string',
            $prefix . 'performed.performedRange.high.code' => 'nullable|string',
        ];
    }
}
