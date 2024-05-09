<?php

namespace App\Http\Requests\Fhir\Search;

use Illuminate\Foundation\Http\FormRequest;

class KfaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'size' => 'required|integer|min:1|max:100',
            'product_type' => 'required|in:farmasi,alkes',
            'from_date' => 'sometimes|date_format:Y-m-d',
            'to_date' => 'sometimes|date_format:Y-m-d',
            'farmalkes_type' => 'sometimes|string',
            'keyword' => 'sometimes|string',
            'template_code' => 'sometimes|string',
            'packaging_code' => 'sometimes|string',
        ];
    }
}
