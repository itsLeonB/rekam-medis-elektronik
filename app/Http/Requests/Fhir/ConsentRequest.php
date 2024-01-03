<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;

class ConsentRequest extends FhirRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|string|exists:identifiers,value',
            'action' => 'required|in:OPTIN,OPTOUT',
            'agent' => 'required|string'
        ];
    }
}
