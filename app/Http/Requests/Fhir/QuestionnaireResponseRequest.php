<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\QuestionnaireResponse;
use Illuminate\Validation\Rule;

class QuestionnaireResponseRequest extends FhirRequest
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
            $this->baseDataRules('questionnaireResponse.'),
            $this->itemDataRules('item.*.'),
        );
    }


    private function baseAttributeRules(): array
    {
        return [
            'questionnaireResponse' => 'required|array',
            'item' => 'nullable|array'
        ];
    }


    private function baseDataRules($prefix): array
    {
        return [
            $prefix . 'based_on' => 'nullable|array',
            $prefix . 'based_on.*' => 'nullable|string',
            $prefix . 'part_of' => 'nullable|array',
            $prefix . 'part_of.*' => 'nullable|string',
            $prefix . 'questionnaire' => 'nullable|string',
            $prefix . 'status' => ['required', Rule::in(QuestionnaireResponse::STATUS['binding']['valueset']['code'])],
            $prefix . 'subject' => 'nullable|string',
            $prefix . 'encounter' => 'nullable|string',
            $prefix . 'authored' => 'nullable|date',
            $prefix . 'author' => 'nullable|string',
            $prefix . 'source' => 'nullable|string',
        ];
    }


    private function itemDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'item_data.link_id' => 'required|string',
                $prefix . 'item_data.definition' => 'nullable|string',
                $prefix . 'item_data.text' => 'nullable|string',
                $prefix . 'answer' => 'nullable|array',
                $prefix . 'answer.*' => 'nullable|array',
                $prefix . 'answer.*.value.valueBoolean' => 'nullable|boolean',
                $prefix . 'answer.*.value.valueDecimal' => 'nullable|numeric',
                $prefix . 'answer.*.value.valueInteger' => 'nullable|integer',
                $prefix . 'answer.*.value.valueDate' => 'nullable|date',
                $prefix . 'answer.*.value.valueDateTime' => 'nullable|date',
                $prefix . 'answer.*.value.valueTime' => 'nullable|date|date_format:H:i:s',
                $prefix . 'answer.*.value.valueString' => 'nullable|string',
                $prefix . 'answer.*.value.valueUri' => 'nullable|string',
            ],
            $this->getAttachmentDataRules($prefix . 'answer.*.value.valueAttachment'),
            $this->getCodingDataRules($prefix . 'answer.*.value.valueCoding'),
            $this->getQuantityDataRules($prefix . 'answer.*.value.valueQuantity'),
            $this->getReferenceDataRules($prefix . 'answer.*.value.valueReference', true),
        );
    }
}
