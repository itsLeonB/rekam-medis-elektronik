<?php

namespace App\Http\Requests\Fhir\Search;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Validation\Rule;

class PatientSearchRequest extends FhirRequest
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
            'gender' => ['sometimes', Rule::in(Patient::GENDER['binding']['valueset'])],
            'birthdate' => 'sometimes|required_with:gender|date',
            'name' => 'sometimes|required_with:gender|string',
        ];
    }
}
