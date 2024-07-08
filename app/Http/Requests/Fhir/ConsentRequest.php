<?php

namespace App\Http\Requests\Fhir;

use Illuminate\Foundation\Http\FormRequest;

class ConsentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|string',
            'action' => 'required|in:OPTIN,OPTOUT',
            'agent' => 'required|string'
        ];
    }
}
