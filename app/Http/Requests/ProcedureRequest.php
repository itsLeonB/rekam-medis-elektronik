<?php

namespace App\Http\Requests;

use App\Models\Procedure;
use App\Models\ProcedureFollowUp;
use App\Models\ProcedurePerformer;
use Illuminate\Validation\Rule;

class ProcedureRequest extends FhirRequest
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
            $this->baseDataRules(),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getReferenceDataRules('basedOn.*.'),
            $this->getReferenceDataRules('partOf.*.'),
            $this->performerDataRules(),
            $this->reasonDataRules(),
            $this->getCodeableConceptDataRules('bodySite.*.'),
            $this->getReferenceDataRules('report.*.'),
            $this->complicationDataRules(),
            $this->getCodeableConceptDataRules('followUp.*.', ProcedureFollowUp::CODE),
            $this->getAnnotationDataRules('note.*.'),
            $this->focalDeviceDataRules(),
            $this->itemUsedDataRules()
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'procedure' => 'required|array',
            'identifier' => 'nullable|array',
            'basedOn' => 'nullable|array',
            'partOf' => 'nullable|array',
            'performer' => 'nullable|array',
            'reason' => 'nullable|array',
            'bodySite' => 'nullable|array',
            'report' => 'nullable|array',
            'complication' => 'nullable|array',
            'followUp' => 'nullable|array',
            'note' => 'nullable|array',
            'focalDevice' => 'nullable|array',
            'itemUsed' => 'nullable|array'
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            [
                'procedure.status' => ['required', Rule::in(Procedure::STATUS_CODE)],
                'procedure.status_reason' => 'nullable|integer',
                'procedure.category' => ['nullable', Rule::in(Procedure::CATEGORY_CODE)],
                'procedure.code_system' => 'required|string',
                'procedure.code_code' => 'required|string',
                'procedure.code_display' => 'required|string',
                'procedure.subject' => 'required|string',
                'procedure.encounter' => 'required|string',
                'procedure.recorder' => 'nullable|string',
                'procedure.asserter' => 'nullable|string',
                'procedure.location' => 'nullable|string',
                'procedure.outcome' => ['nullable', Rule::in(Procedure::OUTCOME_CODE)],

            ],
            $this->getPerformedDataRules('procedure.')
        );
    }

    private function performerDataRules(): array
    {
        return [
            'performer.*.function' => 'nullable|integer|gte:0',
            'performer.*.actor' => 'required|string',
            'performer.*.on_behalf_of' => 'nullable|string',
        ];
    }

    private function reasonDataRules(): array
    {
        return array_merge(
            $this->getCodeableConceptDataRules('reason.*.'),
            $this->getReferenceDataRules('reason.*.')
        );
    }

    private function complicationDataRules(): array
    {
        return array_merge(
            $this->getCodeableConceptDataRules('complication.*.'),
            $this->getReferenceDataRules('complication.*.')
        );
    }

    private function focalDeviceDataRules(): array
    {
        return array_merge(
            $this->getCodeableConceptDataRules('focalDevice.*.'),
            $this->getReferenceDataRules('focalDevice.*.')
        );
    }

    private function itemUsedDataRules(): array
    {
        return array_merge(
            $this->getCodeableConceptDataRules('itemUsed.*.'),
            $this->getReferenceDataRules('itemUsed.*.')
        );
    }
}
