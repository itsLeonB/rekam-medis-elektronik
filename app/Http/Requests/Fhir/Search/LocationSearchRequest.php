<?php

namespace App\Http\Requests\Fhir\Search;

use App\Http\Requests\FhirRequest;

class LocationSearchRequest extends FhirRequest
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
            'organization' => 'sometimes|string',
        ];
    }
}
