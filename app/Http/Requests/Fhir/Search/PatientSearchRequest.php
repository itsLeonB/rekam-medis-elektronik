<?php

namespace App\Http\Requests\Fhir\Search;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientSearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identifier' => 'sometimes|string',
            'gender' => ['sometimes', Rule::in(config('app.terminologi.Patient.gender'))],
            'birthdate' => 'sometimes|required_with:gender|date',
            'name' => 'sometimes|required_with:gender|string',
        ];
    }
}
