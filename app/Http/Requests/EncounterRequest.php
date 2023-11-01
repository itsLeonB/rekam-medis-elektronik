<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EncounterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if (!Auth::check()) {
        //     abort(403, 'Unauthorized action.');
        // }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Encounter attributes
            'encounter' => 'required|array',
            'identifier' => 'required|array',
            'status_history' => 'required|array',
            'class_history' => 'nullable|array',
            'participant' => 'required|array',
            'reason' => 'nullable|array',
            'diagnosis' => 'nullable|array',
            'hospitalization' => 'nullable|array',

            // Encounter base data
            'encounter.status' => ['required', 'string', Rule::in(['planned', 'arrived', 'triaged', 'in-progress', 'onleave', 'finished', 'cancelled', 'entered-in-error', 'unknown'])],
            'encounter.class' => 'required|string|max:6',
            'encounter.service_type' => 'nullable|integer|gte:0',
            'encounter.priority' => 'nullable|string|max:3',
            'encounter.subject' => 'required|string',
            'encounter.episode_of_care' => 'nullable|string',
            'encounter.based_on' => 'nullable|string',
            'encounter.period_start' => 'required|date',
            'encounter.period_end' => 'nullable|date',
            'encounter.account' => 'nullable|string',
            'encounter.location' => 'required|string',
            'encounter.service_provider' => 'required|string',
            'encounter.part_of' => 'nullable|string',

            // Encounter identifier data
            'identifier.*.system' => 'required|string',
            'identifier.*.use' => ['required', Rule::in(['usual', 'official', 'temp', 'secondary', 'old'])],
            'identifier.*.value' => 'required|string',

            // Encounter status history data
            'status_history.*.status' => ['required', Rule::in(['planned', 'arrived', 'triaged', 'in-progress', 'onleave', 'finished', 'cancelled', 'entered-in-error', 'unknown'])],
            'status_history.*.period_start' => 'required|date',
            'status_history.*.period_end' => 'nullable|date',

            // Encounter class history data
            'class_history.*.class' => 'required|string|max:6',
            'class_history.*.period_start' => 'required|date',
            'class_history.*.period_end' => 'nullable|date',

            // Encounter participant data
            'participant.*.type' => 'required|string|max:10',
            'participant.*.individual' => 'required|string',

            // Encounter reason data
            'reason.*.code' => 'nullable|integer|gte:0',
            'reason.*.reference' => 'nullable|string',

            // Encounter diagnosis data
            'diagnosis.*.condition_reference' => 'required|string',
            'diagnosis.*.condition_display' => 'required|string',
            'diagnosis.*.use' => ['nullable', Rule::in(['AD', 'DD', 'CC', 'CM', 'pre-op', 'post-op', 'billing'])],
            'diagnosis.*.rank' => 'nullable|integer',

            // Encounter hospitalization data
            'hospitalization.*.hospitalization_data.preadmission_identifier_system' => 'nullable|string',
            'hospitalization.*.hospitalization_data.preadmission_identifier_use' => ['nullable', Rule::in(['usual', 'official', 'temp', 'secondary', 'old'])],
            'hospitalization.*.hospitalization_data.preadmission_identifier_value' => 'nullable|string',
            'hospitalization.*.hospitalization_data.origin' => 'nullable|string',
            'hospitalization.*.hospitalization_data.admit_source' => 'nullable|string',
            'hospitalization.*.hospitalization_data.re_admission' => ['nullable', Rule::in(['R'])],
            'hospitalization.*.hospitalization_data.destination' => 'nullable|string',
            'hospitalization.*.hospitalization_data.discharge_disposition' => 'nullable|string',

            // Encounter hospitalization diet data
            'hospitalization.*.diet.*.system' => 'nullable|string',
            'hospitalization.*.diet.*.code' => 'required|string',
            'hospitalization.*.diet.*.display' => 'nullable|string',

            // Encounter hospitalization special arrangement data
            'hospitalization.*.special_arrangement.*.system' => 'nullable|string',
            'hospitalization.*.special_arrangement.*.code' => 'required|string',
            'hospitalization.*.special_arrangement.*.display' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        // create the corresponding validation error message according to the rules above
        return [
            //Untuk required
            'encounter.status.required' => 'Status kunjungan pasien harus diisi',
            'encounter.class.required' => 'Jenis kunjungan pasien harus diisi',
            'encounter.subject.required' => 'Data pasien harus diisi',
            'encounter.period_start.required' => 'Data waktu mulainya kunjungan pasien harus diisi',
            'encounter.location.required' => 'Data lokasi kunjungan pasien harus diisi',
            'encounter.service_provider.required' => 'Data fasyankes yang dikunjungi pasien harus diisi',

            'identifier.*.system.required' => 'Identifier system harus diisi',
            'identifier.*.use.required' => 'Identifier use harus diisi',
            'identifier.*.value.required' => 'Identifier value harus diisi',

            'status_history.*.status.required' => 'Data status kunjungan historis harus diisi',
            'status_history.*.period_start.required' => 'Data historis status waktu mulainya kunjungan harus diisi',

            'class_history.*.class.required' => 'Data jenis kunjungan historis harus diisi',
            'class_history.*.period_start.required' => 'Data historis kelas waktu mulainya kunjungan harus diisi',

            'participant.*.type.required' => 'Data tipe tenaga kesehatan yang bertugas harus diisi',
            'participant.*.individual.required' => 'Data ID tenaga kesehatan yang bertugas harus diisi',

            'diagnosis.*.condition_reference.required' => 'Data ID keluhan pasien harus diisi',
            'diagnosis.*.condition_rdisplay.required' => 'Data tertulis keluhan pasien harus diisi',

            'hospitalization.*.diet.*.code.required' => 'Data kode pantangan konsumsi pasien harus diisi',

            'hospitalization.*.special_arrangement.*.code.required' => 'Data kode kebutuhan khusus pasien harus diisi',

            //Untuk Rule::in
            'encounter.status.in' => 'Harus termasuk "planned", "arrived", "triaged", "in-progress", "onleave", "finished", "cancelled", "entered-in-error", atau "unknown"',

            'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

            'status_history.*.status.in' => 'Harus termasuk "planned", "arrived", "triaged", "in-progress", "onleave", "finished", "cancelled", "entered-in-error", atau "unknown"',

            //Untuk gte
            'encounter.service_type.gte' => 'Nilai tipe layanan tidak boleh negatif',

            'reason.*.code.gte' => 'Nilai kode alasan kunjungan tidak boleh negatif',

            //Untuk integer
            'encounter.service_type.integer' => 'Data tipe layanan harus berbentuk angka',

            'reason.*.code.integer' => 'Data kode alasan kunjungan harus berbentuk angka',

            'diagnosis.*.rank.integer' => 'Data peringkat diagnosis harus berbentuk angka',

            //Untuk max
            'encounter.class.max' => 'Data jenis kunjungan maksimal 6 karakter',
            'encounter.priority.max' => 'Data prioritas kunjungan maksimal 3 karakter',

            'class_history.*.class.max' => 'Data jenis kunjungan historis maksimal 6 karakter',

            'participant.*.type.max' => 'Data tipe tenaga kesehatan yang bertugas maksimal 10 karakter'
        ];
    }
}
