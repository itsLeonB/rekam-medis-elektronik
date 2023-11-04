<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\CodeSystemLoinc;
use App\Models\Observation;
use App\Models\ObservationCategory;
use App\Models\ObservationInterpretation;
use App\Models\ObservationReferenceRange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ObservationRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Observation attributes
            'observation' => 'required|array',
            'identifier' => 'nullable|array',
            'based_on' => 'nullable|array',
            'part_of' => 'nullable|array',
            'category' => 'nullable|array',
            'focus' => 'nullable|array',
            'performer' => 'nullable|array',
            'interpretation' => 'nullable|array',
            'note' => 'nullable|array',
            'reference_range' => 'nullable|array',
            'has_member' => 'nullable|array',
            'derived_from' => 'nullable|array',
            'component' => 'nullable|array',

            // Observation base data
            'observation.status' => ['required', Rule::in(Observation::STATUS_CODE)],
            'observation.code_system' => 'nullable|string',
            'observation.code_code' => 'required|string',
            'observation.code_display' => 'nullable|string',
            'observation.subject' => 'required|string',
            'observation.encounter' => 'required|string',
            'observation.effective' => 'nullable|array',
            'observation.effective.effectiveDateTime' => 'nullable|date',
            'observation.effective.effectivePeriod' => 'nullable|array',
            'observation.effective.effectivePeriod.start' => 'nullable|date',
            'observation.effective.effectivePeriod.end' => 'nullable|date',
            'observation.effective.effectiveTiming' => 'nullable|array',
            'observation.effective.effectiveTiming.event' => 'nullable|array',
            'observation.effective.effectiveTiming.event.*' => 'nullable|date',
            'observation.effective.effectiveTiming.repeat' => 'nullable|array',
            'observation.effective.effectiveTiming.repeat.boundsDuration' => 'nullable|array',
            'observation.effective.effectiveTiming.repeat.boundsDuration.value' => 'nullable|integer',
            'observation.effective.effectiveTiming.repeat.boundsDuration.comparator' => 'nullable|string|max:2',
            'observation.effective.effectiveTiming.repeat.boundsDuration.unit' => 'nullable|string|max:2',
            'observation.effective.effectiveTiming.repeat.boundsDuration.system' => 'nullable|string',
            'observation.effective.effectiveTiming.repeat.boundsDuration.code' => 'nullable|string',
            'observation.effective.effectiveTiming.repeat.count' => 'nullable|integer|gte:0',
            'observation.effective.effectiveTiming.repeat.countMax' => 'nullable|integer|gte:0',
            'observation.effective.effectiveTiming.repeat.duration' => 'nullable|decimal',
            'observation.effective.effectiveTiming.repeat.durationMax' => 'nullable|decimal',
            'observation.effective.effectiveTiming.repeat.frequency' => 'nullable|integer|gte:0',
            'observation.effective.effectiveTiming.repeat.frequencyMax' => 'nullable|integer|gte:0',
            'observation.effective.effectiveTiming.repeat.period' => 'nullable|decimal',
            'observation.effective.effectiveTiming.repeat.periodMax' => 'nullable|decimal',
            'observation.effective.effectiveTiming.repeat.periodUnit' => ['nullable', Rule::in(Constants::PERIOD_UNIT)],
            'observation.effective.effectiveTiming.repeat.dayOfWeek' => 'nullable|array',
            'observation.effective.effectiveTiming.repeat.dayOfWeek.*' => ['nullable', Rule::in(Constants::DAY_OF_WEEK)],
            'observation.effective.effectiveTiming.repeat.timeOfDay' => 'nullable|array',
            'observation.effective.effectiveTiming.repeat.timeOfDay.*' => 'nullable|date_format:H:i:s',
            'observation.effective.effectiveTiming.repeat.when' => 'nullable|array',
            'observation.effective.effectiveTiming.repeat.when.*' => ['nullable', Rule::in(Constants::TIMING_EVENT_CODE)],
            'observation.effective.effectiveTiming.repeat.offset' => 'nullable|integer|gte:0',
            'observation.effective.effectiveTiming.code' => 'nullable|array',
            'observation.effective.effectiveTiming.code.coding' => 'nullable|array',
            'observation.effective.effectiveTiming.code.coding.*.system' => 'nullable|string',
            'observation.effective.effectiveTiming.code.coding.*.code' => ['nullable', Rule::in(Constants::TIMING_ABBREVIATION_CODE)],
            'observation.effective.effectiveTiming.code.coding.*.display' => 'nullable|string',
            'observation.effective.effectiveTiming.code.text' => 'nullable|string',
            'observation.effective.effectiveInstant' => 'nullable|date',
            'observation.issued' => 'nullable|date',
            'observation.value' => 'nullable|array',
            'observation.value.quantity' => 'nullable|array',
            'observation.value.quantity.value' => 'nullable|decimal',
            'observation.value.quantity.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'observation.value.quantity.unit' => 'nullable|string',
            'observation.value.quantity.system' => 'nullable|string',
            'observation.value.quantity.code' => 'nullable|string',
            'observation.value.valueCodeableConcept' => 'nullable|array',
            'observation.value.valueCodeableConcept.coding' => 'nullable|array',
            'observation.value.valueCodeableConcept.coding.*.system' => 'nullable|string',
            'observation.value.valueCodeableConcept.coding.*.code' => 'nullable|string',
            'observation.value.valueCodeableConcept.coding.*.display' => 'nullable|string',
            'observation.value.valueCodeableConcept.text' => 'nullable|string',
            'observation.value.valueString' => 'nullable|string',
            'observation.value.valueBoolean' => 'nullable|boolean',
            'observation.value.valueInteger' => 'nullable|integer',
            'observation.value.valueRange' => 'nullable|array',
            'observation.value.valueRange.low' => 'nullable|array',
            'observation.value.valueRange.low.value' => 'nullable|decimal',
            'observation.value.valueRange.low.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'observation.value.valueRange.low.unit' => 'nullable|string',
            'observation.value.valueRange.low.system' => 'nullable|string',
            'observation.value.valueRange.low.code' => 'nullable|string',
            'observation.value.valueRange.high' => 'nullable|array',
            'observation.value.valueRange.high.value' => 'nullable|decimal',
            'observation.value.valueRange.high.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'observation.value.valueRange.high.unit' => 'nullable|string',
            'observation.value.valueRange.high.system' => 'nullable|string',
            'observation.value.valueRange.high.code' => 'nullable|string',
            'observation.value.valueRatio' => 'nullable|array',
            'observation.value.valueRatio.numerator' => 'nullable|array',
            'observation.value.valueRatio.numerator.value' => 'nullable|decimal',
            'observation.value.valueRatio.numerator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'observation.value.valueRatio.numerator.unit' => 'nullable|string',
            'observation.value.valueRatio.numerator.system' => 'nullable|string',
            'observation.value.valueRatio.numerator.code' => 'nullable|string',
            'observation.value.valueRatio.denominator' => 'nullable|array',
            'observation.value.valueRatio.denominator.value' => 'nullable|decimal',
            'observation.value.valueRatio.denominator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'observation.value.valueRatio.denominator.unit' => 'nullable|string',
            'observation.value.valueRatio.denominator.system' => 'nullable|string',
            'observation.value.valueRatio.denominator.code' => 'nullable|string',
            'observation.value.valueSampledData' => 'nullable|array',
            'observation.value.valueSampledData.origin' => 'nullable|array',
            'observation.value.valueSampledData.origin.value' => 'nullable|decimal',
            'observation.value.valueSampledData.origin.unit' => 'nullable|string',
            'observation.value.valueSampledData.origin.system' => 'nullable|string',
            'observation.value.valueSampledData.origin.code' => 'nullable|string',
            'observation.value.valueSampledData.period' => 'nullable|decimal',
            'observation.value.valueSampledData.factor' => 'nullable|decimal',
            'observation.value.valueSampledData.lowerLimit' => 'nullable|decimal',
            'observation.value.valueSampledData.upperLimit' => 'nullable|decimal',
            'observation.value.valueSampledData.dimensions' => 'nullable|integer|gte:0',
            'observation.value.valueSampledData.data' => 'nullable|string',
            'observation.value.valueTime' => 'nullable|date_format:H:i:s',
            'observation.value.valueDateTime' => 'nullable|date',
            'observation.value.valuePeriod' => 'nullable|array',
            'observation.value.valuePeriod.start' => 'nullable|date',
            'observation.value.valuePeriod.end' => 'nullable|date',
            'observation.data_absent_reason' => ['nullable', Rule::in(Observation::DATA_ABSENT_REASON_CODE)],
            'observation.body_site_system' => 'nullable|string',
            'observation.body_site_code' => 'nullable|string',
            'observation.body_site_display' => 'nullable|string',
            'observation.method_system' => 'nullable|string',
            'observation.method_code' => 'nullable|string',
            'observation.method_display' => 'nullable|string',
            'observation.specimen' => 'nullable|string',
            'observation.device' => 'nullable|string',

            // Observation identifier data
            'identifier.*.system' => 'required|string',
            'identifier.*.use' => ['required', Rule::in(Constants::IDENTIFIER_USE)],
            'identifier.*.value' => 'required|string',

            // Observation basedOn data
            'based_on.*.reference' => 'required|string',

            // Observation partOf data
            'part_of.*.reference' => 'required|string',

            // Observation category data
            'category.*.system' => 'required|string',
            'category.*.code' => ['required', Rule::in(ObservationCategory::CODE)],
            'category.*.display' => 'required|string',

            // Observation focus data
            'focus.*.reference' => 'required|string',

            // Observation performer data
            'performer.*.reference' => 'required|string',

            // Observation interpretation data
            'interpretation.*.system' => 'required|string',
            'interpretation.*.code' => ['required', Rule::in(ObservationInterpretation::CODE)],
            'interpretation.*.display' => 'required|string',

            // Observation note data
            'note.*.author' => 'nullable|array',
            'note.*.author.authorString' => 'nullable|string',
            'note.*.author.authorReference' => 'nullable|array',
            'note.*.author.authorReference.reference' => 'nullable|string',
            'note.*.time' => 'nullable|date',
            'note.*.text' => 'nullable|string',

            // Observation reference range data
            'reference_range.*.value_low' => 'nullable|decimal',
            'reference_range.*.value_high' => 'nullable|decimal',
            'reference_range.*.unit' => 'nullable|string',
            'reference_range.*.system' => 'nullable|string',
            'reference_range.*.code' => 'nullable|string',
            'reference_range.*.type' => ['nullable', Rule::in(ObservationReferenceRange::CODE)],
            'reference_range.*.applies_to' => 'nullable|array',
            'reference_range.*.applies_to.*.coding' => 'nullable|array',
            'reference_range.*.applies_to.*.coding.*.system' => 'nullable|string',
            'reference_range.*.applies_to.*.coding.*.code' => 'nullable|string',
            'reference_range.*.applies_to.*.coding.*.display' => 'nullable|string',
            'reference_range.*.applies_to.*.text' => 'nullable|string',
            'reference_range.*.age_low' => 'nullable|integer|gte:0',
            'reference_range.*.age_high' => 'nullable|integer|gte:0',
            'reference_range.*.text' => 'nullable|string',

            // Observation hasMember data
            'has_member.*.reference' => 'required|string',

            // Observation derivedFrom data
            'derived_from.*.reference' => 'required|string',

            // Observation component data
            'component.*.component_data' => 'required|array',
            'component.*.component_data.code_system' => 'nullable|string',
            'component.*.component_data.code_code' => 'required|string',
            'component.*.component_data.code_display' => 'nullable|string',
            'component.*.component_data.value' => 'nullable|array',
            'component.*.component_data.value.quantity' => 'nullable|array',
            'component.*.component_data.value.quantity.value' => 'nullable|decimal',
            'component.*.component_data.value.quantity.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'component.*.component_data.value.quantity.unit' => 'nullable|string',
            'component.*.component_data.value.quantity.system' => 'nullable|string',
            'component.*.component_data.value.quantity.code' => 'nullable|string',
            'component.*.component_data.value.valueCodeableConcept' => 'nullable|array',
            'component.*.component_data.value.valueCodeableConcept.coding' => 'nullable|array',
            'component.*.component_data.value.valueCodeableConcept.coding.*.system' => 'nullable|string',
            'component.*.component_data.value.valueCodeableConcept.coding.*.code' => 'nullable|string',
            'component.*.component_data.value.valueCodeableConcept.coding.*.display' => 'nullable|string',
            'component.*.component_data.value.valueCodeableConcept.text' => 'nullable|string',
            'component.*.component_data.value.valueString' => 'nullable|string',
            'component.*.component_data.value.valueBoolean' => 'nullable|boolean',
            'component.*.component_data.value.valueInteger' => 'nullable|integer',
            'component.*.component_data.value.valueRange' => 'nullable|array',
            'component.*.component_data.value.valueRange.low' => 'nullable|array',
            'component.*.component_data.value.valueRange.low.value' => 'nullable|decimal',
            'component.*.component_data.value.valueRange.low.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'component.*.component_data.value.valueRange.low.unit' => 'nullable|string',
            'component.*.component_data.value.valueRange.low.system' => 'nullable|string',
            'component.*.component_data.value.valueRange.low.code' => 'nullable|string',
            'component.*.component_data.value.valueRange.high' => 'nullable|array',
            'component.*.component_data.value.valueRange.high.value' => 'nullable|decimal',
            'component.*.component_data.value.valueRange.high.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'component.*.component_data.value.valueRange.high.unit' => 'nullable|string',
            'component.*.component_data.value.valueRange.high.system' => 'nullable|string',
            'component.*.component_data.value.valueRange.high.code' => 'nullable|string',
            'component.*.component_data.value.valueRatio' => 'nullable|array',
            'component.*.component_data.value.valueRatio.numerator' => 'nullable|array',
            'component.*.component_data.value.valueRatio.numerator.value' => 'nullable|decimal',
            'component.*.component_data.value.valueRatio.numerator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'component.*.component_data.value.valueRatio.numerator.unit' => 'nullable|string',
            'component.*.component_data.value.valueRatio.numerator.system' => 'nullable|string',
            'component.*.component_data.value.valueRatio.numerator.code' => 'nullable|string',
            'component.*.component_data.value.valueRatio.denominator' => 'nullable|array',
            'component.*.component_data.value.valueRatio.denominator.value' => 'nullable|decimal',
            'component.*.component_data.value.valueRatio.denominator.comparator' => ['nullable', Rule::in(Constants::COMPARATOR)],
            'component.*.component_data.value.valueRatio.denominator.unit' => 'nullable|string',
            'component.*.component_data.value.valueRatio.denominator.system' => 'nullable|string',
            'component.*.component_data.value.valueRatio.denominator.code' => 'nullable|string',
            'component.*.component_data.value.valueSampledData' => 'nullable|array',
            'component.*.component_data.value.valueSampledData.origin' => 'nullable|array',
            'component.*.component_data.value.valueSampledData.origin.value' => 'nullable|decimal',
            'component.*.component_data.value.valueSampledData.origin.unit' => 'nullable|string',
            'component.*.component_data.value.valueSampledData.origin.system' => 'nullable|string',
            'component.*.component_data.value.valueSampledData.origin.code' => 'nullable|string',
            'component.*.component_data.value.valueSampledData.period' => 'nullable|decimal',
            'component.*.component_data.value.valueSampledData.factor' => 'nullable|decimal',
            'component.*.component_data.value.valueSampledData.lowerLimit' => 'nullable|decimal',
            'component.*.component_data.value.valueSampledData.upperLimit' => 'nullable|decimal',
            'component.*.component_data.value.valueSampledData.dimensions' => 'nullable|integer|gte:0',
            'component.*.component_data.value.valueSampledData.data' => 'nullable|string',
            'component.*.component_data.value.valueTime' => 'nullable|date_format:H:i:s',
            'component.*.component_data.value.valueDateTime' => 'nullable|date',
            'component.*.component_data.value.valuePeriod' => 'nullable|array',
            'component.*.component_data.value.valuePeriod.start' => 'nullable|date',
            'component.*.component_data.value.valuePeriod.end' => 'nullable|date',
            'component.*.component_data.data_absent_reason' => ['nullable', Rule::in(Observation::DATA_ABSENT_REASON_CODE)],

            // Observation component interpretation data
            'component.*.interpretation' => 'nullable|array',
            'component.*.interpretation.*.system' => 'required|string',
            'component.*.interpretation.*.code' => ['required', Rule::in(ObservationInterpretation::CODE)],
            'component.*.interpretation.*.display' => 'required|string',

            // Observation component reference range data
            'component.*.reference_range.*.value_low' => 'nullable|decimal',
            'component.*.reference_range.*.value_high' => 'nullable|decimal',
            'component.*.reference_range.*.unit' => 'nullable|string',
            'component.*.reference_range.*.system' => 'nullable|string',
            'component.*.reference_range.*.code' => 'nullable|string',
            'component.*.reference_range.*.type' => ['nullable', Rule::in(ObservationReferenceRange::CODE)],
            'component.*.reference_range.*.applies_to' => 'nullable|array',
            'component.*.reference_range.*.applies_to.*.coding' => 'nullable|array',
            'component.*.reference_range.*.applies_to.*.coding.*.system' => 'nullable|string',
            'component.*.reference_range.*.applies_to.*.coding.*.code' => 'nullable|string',
            'component.*.reference_range.*.applies_to.*.coding.*.display' => 'nullable|string',
            'component.*.reference_range.*.applies_to.*.text' => 'nullable|string',
            'component.*.reference_range.*.age_low' => 'nullable|integer|gte:0',
            'component.*.reference_range.*.age_high' => 'nullable|integer|gte:0',
            'component.*.reference_range.*.text' => 'nullable|string',
        ];
    }
}
