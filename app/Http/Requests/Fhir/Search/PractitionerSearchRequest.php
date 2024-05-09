<?php

namespace App\Http\Requests\Fhir\Search;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PractitionerSearchRequest extends FormRequest
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
            'gender' => ['sometimes', 'required_with:name', Rule::in(config('app.terminologi.Practitioner.gender'))],
            'birthdate' => 'sometimes|required_with:name|string',
        ];
    }
}
