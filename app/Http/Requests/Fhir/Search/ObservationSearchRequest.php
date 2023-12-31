<?php

namespace App\Http\Requests\Fhir\Search;

use App\Http\Requests\FhirRequest;

class ObservationSearchRequest extends FhirRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'sometimes|required_with:based-on|string',
            'encounter' => 'sometimes|string',
            'based-on' => 'sometimes|string',
        ];
    }
}
