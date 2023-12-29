<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\QuestionnaireResponse;
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
            [
                'identifier' => 'nullable|array',
                'basedOn' => 'nullable|array',
                'partOf' => 'nullable|array',
                'questionnaire' => 'nullable|string',
                'status' => ['required', Rule::in(QuestionnaireResponse::STATUS['binding']['valueset']['code'])],
                'subject' => 'nullable|array',
                'encounter' => 'nullable|array',
                'authored' => 'nullable|date',
                'author' => 'nullable|array',
                'source' => 'nullable|array',
                'item' => 'nullable|array',
                'item.*.linkId' => 'sometimes|string',
                'item.*.definition' => 'nullable|string',
                'item.*.text' => 'nullable|string',
                'item.*.answer' => 'nullable|array',
                'item.*.answer.*.valueBoolean' => 'nullable|boolean',
                'item.*.answer.*.valueDecimal' => 'nullable|numeric',
                'item.*.answer.*.valueInteger' => 'nullable|integer',
                'item.*.answer.*.valueDate' => 'nullable|date',
                'item.*.answer.*.valueDateTime' => 'nullable|date',
                'item.*.answer.*.valueTime' => 'nullable|date_format:H:i:s',
                'item.*.answer.*.valueString' => 'nullable|string',
                'item.*.answer.*.valueUri' => 'nullable|url',
                'item.*.answer.*.valueAttachment' => 'nullable|array',
                'item.*.answer.*.valueCoding' => 'nullable|array',
                'item.*.answer.*.valueQuantity' => 'nullable|array',
                'item.*.answer.*.valueReference' => 'nullable|array',
                'item.*.answer.*.item' => 'nullable|array',
                'item.*.item' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.'),
            $this->getReferenceRules('basedOn.*.'),
            $this->getReferenceRules('partOf.*.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('encounter.'),
            $this->getReferenceRules('author.'),
            $this->getReferenceRules('source.'),
            $this->getAttachmentRules('item.*.answer.*.valueAttachment.'),
            $this->getCodingRules('item.*.answer.*.valueCoding.'),
            $this->getQuantityRules('item.*.answer.*.valueQuantity.'),
            $this->getReferenceRules('item.*.answer.*.valueReference.'),
        );
    }
}
