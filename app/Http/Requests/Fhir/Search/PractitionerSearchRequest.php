<?php

namespace App\Http\Requests\Fhir\Search;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\Practitioner;
use Illuminate\Validation\Rule;

class PractitionerSearchRequest extends FhirRequest
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
            'name' => 'sometimes|string',
            'gender' => ['sometimes', 'required_with:name', Rule::in(Practitioner::GENDER['binding']['valueset'])],
            'birthdate' => 'sometimes|required_with:name|string',
        ];
    }
}
