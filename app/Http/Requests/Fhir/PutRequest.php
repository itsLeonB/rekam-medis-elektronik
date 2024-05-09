<?php

namespace App\Http\Requests\Fhir;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|string',
            'resourceType' => ['required', 'string', Rule::in(config('app.resourceTypes'))],
        ];
    }
}
