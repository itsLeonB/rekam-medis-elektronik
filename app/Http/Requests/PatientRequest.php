<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::check()) {
            return true;
        } else {
            Log::error('Error: user tidak terotentikasi.');
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Patient base data
            'patient.active' => 'required|boolean',
            'patient.name' => 'required|string|max:255',
            'patient.prefix' => 'nullable|string|max:255',
            'patient.suffix' => 'nullable|string|max:255',
            'patient.gender' => ['required', 'string', Rule::in(['male', 'female', 'other', 'unknown'])],
            'patient.birth_date' => 'nullable|date',
            'patient.birth_place' => 'nullable|string|max:255',
            'patient.deceased' => 'nullable|array',
            'patient.marital_status' => ['nullable', 'string', Rule::in(['A', 'D', 'I', 'L', 'M', 'P', 'S', 'T', 'U', 'W'])],
            'patient.multiple_birth' => 'nullable|array',
            'patient.language' => 'nullable|string|max:255',

            // Patient identifier data
            'identifier.*.system' => 'required|string|max:255',
            'identifier.*.use' => ['required', 'string', Rule::in(['usual', 'official', 'temp', 'secondary', 'old'])],
            'identifier.*.value' => 'required|string|max:255',

            // Patient telecom data
            'telecom.*.system' => ['required', 'string', Rule::in(['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other'])],
            'telecom.*.use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'mobile'])],
            'telecom.*.value' => 'required|string|max:255',

            // Patient address data
            'address.*.use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'billing'])],
            'address.*.line' => 'required|string',
            'address.*.country' => 'required|string|max:255',
            'address.*.postal_code' => 'required|string|max:255',
            'address.*.province' => 'required|integer|gte:0|digits:2',
            'address.*.city' => 'required|integer|gte:0|digits:4',
            'address.*.district' => 'required|integer|gte:0|digits:6',
            'address.*.village' => 'required|integer|gte:0|digits:10',
            'address.*.rt' => 'required|integer|gte:0|max_digits:2',
            'address.*.rw' => 'required|integer|gte:0|max_digits:2',

            // Patient contact data
            'contact.*.contact_data.relationship' => ['required', 'string', Rule::in(['BP', 'CP', 'EP', 'PR', 'E', 'C', 'F', 'I', 'N', 'S', 'U'])],
            'contact.*.contact_data.name' => 'required|string|max:255',
            'contact.*.contact_data.prefix' => 'nullable|string|max:255',
            'contact.*.contact_data.suffix' => 'nullable|string|max:255',
            'contact.*.contact_data.gender' => ['required', 'string', Rule::in(['male', 'female', 'other', 'unknown'])],
            'contact.*.contact_data.address_use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'billing'])],
            'contact.*.contact_data.address_line' => 'required|string',
            'contact.*.contact_data.country' => 'required|string|max:255',
            'contact.*.contact_data.postal_code' => 'required|string|max:255',
            'contact.*.contact_data.province' => 'required|integer|gte:0|digits:2',
            'contact.*.contact_data.city' => 'required|integer|gte:0|digits:4',
            'contact.*.contact_data.district' => 'required|integer|gte:0|digits:6',
            'contact.*.contact_data.village' => 'required|integer|gte:0|digits:10',
            'contact.*.contact_data.rt' => 'required|integer|gte:0|max_digits:2',
            'contact.*.contact_data.rw' => 'required|integer|gte:0|max_digits:2',

            // Patient contact telecom data
            'contact.*.telecom.*.system' => ['required', 'string', Rule::in(['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other'])],
            'contact.*.telecom.*.use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'mobile'])],
            'contact.*.telecom.*.value' => 'required|string|max:255',

            // General practitioner data
            'general_practitioner.*.reference' => 'required|string|max:255'
        ];
    }


    public function messages(): array
    {
        // create the corresponding validation error message according to the rules above
        return [
            'patient.active.required' => 'Harus dipilih.',
            'patient.name.required' => 'Nama harus diisi.',
        ];
    }
}
