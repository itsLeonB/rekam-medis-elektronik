<?php

namespace App\Http\Requests;

use App\Models\Procedure;
use App\Models\ProcedureFocalDevice;
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
            $this->baseDataRules('procedure.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->performerDataRules('performer.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->focalDeviceDataRules('focalDevice.*.'),
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'procedure' => 'required|array',
            'identifier' => 'nullable|array',
            'performer' => 'nullable|array',
            'note' => 'nullable|array',
            'focalDevice' => 'nullable|array',
        ];
    }

    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'based_on' => 'nullable|array',
                $prefix . 'based_on.*' => 'required|string',
                $prefix . 'part_of' => 'nullable|array',
                $prefix . 'part_of.*' => 'required|string',
                $prefix . 'status' => ['required', Rule::in(Procedure::STATUS['binding']['valueset']['code'])],
                $prefix . 'status_reason' => ['nullable', Rule::exists(Procedure::STATUS_REASON['binding']['valueset']['table'], 'code')],
                $prefix . 'category' => ['nullable', Rule::in(Procedure::CATEGORY['binding']['valueset']['code'])],
                $prefix . 'code_system' => 'nullable|string',
                $prefix . 'code_code' => 'required|string',
                $prefix . 'code_display' => 'nullable|string',
                $prefix . 'subject' => 'required|string',
                $prefix . 'encounter' => 'required|string',
                $prefix . 'recorder' => 'nullable|string',
                $prefix . 'asserter' => 'nullable|string',
                $prefix . 'location' => 'nullable|string',
                $prefix . 'reason_code' => 'nullable|array',
                $prefix . 'reason_code.*' => ['required', Rule::exists(Procedure::REASON_CODE['binding']['valueset']['table'], 'code')],
                $prefix . 'reason_reference' => 'nullable|array',
                $prefix . 'reason_reference.*' => 'required|string',
                $prefix . 'body_site' => 'nullable|array',
                $prefix . 'body_site.*' => ['required', Rule::exists(Procedure::BODY_SITE['binding']['valueset']['table'], 'code')],
                $prefix . 'outcome' => ['nullable', Rule::in(Procedure::OUTCOME['binding']['valueset']['code'])],
                $prefix . 'report' => 'nullable|array',
                $prefix . 'report.*' => 'required|string',
                $prefix . 'complication' => 'nullable|array',
                $prefix . 'complication.*' => ['required', Rule::exists(Procedure::COMPLICATION['binding']['valueset']['table'], 'code')],
                $prefix . 'complication_detail' => 'nullable|array',
                $prefix . 'complication_detail.*' => 'required|string',
                $prefix . 'follow_up' => 'nullable|array',
                $prefix . 'follow_up.*' => ['required', Rule::in(Procedure::FOLLOW_UP['binding']['valueset']['code'], 'code')],
                $prefix . 'used_reference' => 'nullable|array',
                $prefix . 'used_reference.*' => 'required|string',
                $prefix . 'used_code' => 'nullable|array',
                $prefix . 'used_code.*' => 'required|integer|gte:0',

            ],
            $this->getPerformedDataRules($prefix)
        );
    }

    private function performerDataRules($prefix): array
    {
        return [
            $prefix . 'function' => ['nullable', Rule::exists(ProcedurePerformer::FUNCTION['binding']['valueset']['table'], 'code')],
            $prefix . 'actor' => 'required|string',
            $prefix . 'on_behalf_of' => 'nullable|string',
        ];
    }

    private function focalDeviceDataRules($prefix): array
    {
        return [
            $prefix . 'action' => ['nullable', Rule::exists(ProcedureFocalDevice::ACTION['binding']['valueset']['table'], 'code')],
            $prefix . 'manipulated' => 'required|string',
        ];
    }
}
