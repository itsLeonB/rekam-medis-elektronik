<?php

namespace App\Http\Requests;

use App\Models\QuestionnaireResponse;
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
            $this->itemDataRules('item.*.answer.*.item.*.'),
            $this->itemDataRules('item.*.item.*.'),
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
                $prefix . 'link_id' => 'required|string',
                $prefix . 'definition' => 'nullable|string',
                $prefix . 'text' => 'nullable|string',
                $prefix . 'answer' => 'nullable|array',
                $prefix . 'answer.*' => 'nullable|array',
                $prefix . 'answer.*.valueBoolean' => 'nullable|boolean',
                $prefix . 'answer.*.valueDecimal' => 'nullable|numeric',
                $prefix . 'answer.*.valueInteger' => 'nullable|integer',
                $prefix . 'answer.*.valueDate' => 'nullable|date',
                $prefix . 'answer.*.valueDateTime' => 'nullable|date',
                $prefix . 'answer.*.valueDateTime' => 'nullable|date',
                $prefix . 'answer.*.valueTime' => 'nullable|date|date_format:H:i:s',
                $prefix . 'answer.*.valueString' => 'nullable|string',
                $prefix . 'answer.*.valueUri' => 'nullable|string',
            ],
            $this->getAttachmentDataRules($prefix . 'answer.*.valueAttachment'),
            $this->getCodingDataRules($prefix . 'answer.*.valueCoding'),
            $this->getQuantityDataRules($prefix . 'answer.*.valueQuantity'),
            $this->getReferenceDataRules($prefix . 'answer.*.valueReference', true),
        );
    }
}
