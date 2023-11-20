<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\Encounter;
use App\Models\EncounterDiagnosis;
use App\Models\EncounterHospitalization;
use App\Models\EncounterHospitalizationDiet;
use App\Models\EncounterHospitalizationSpecialArrangement;
use Illuminate\Validation\Rule;

class EncounterRequest extends FhirRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(
            $this->baseAttributeRules(),
            $this->baseDataRules(),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->statusHistoryDataRules(),
            $this->classHistoryDataRules(),
            $this->participantDataRules(),
            $this->reasonDataRules(),
            $this->diagnosisDataRules(),
            $this->hospitalizationDataRules(),
            $this->getCodeableConceptDataRules('hospitalization.*.diet.*.', EncounterHospitalizationDiet::PREFERENCE_CODE),
            $this->getCodeableConceptDataRules('hospitalization.*.specialArrangement.*.', EncounterHospitalizationSpecialArrangement::CODE),
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'encounter' => 'required|array',
            'identifier' => 'nullable|array',
            'statusHistory' => 'required|array',
            'classHistory' => 'nullable|array',
            'participant' => 'required|array',
            'reason' => 'nullable|array',
            'diagnosis' => 'nullable|array',
            'hospitalization' => 'nullable|array',
        ];
    }

    private function baseDataRules(): array
    {
        return [
            'encounter.status' => ['required', 'string', Rule::in(Encounter::STATUS_CODE)],
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
        ];
    }

    private function statusHistoryDataRules(): array
    {
        return [
            'statusHistory.*.status' => ['required', Rule::in(Encounter::STATUS_CODE)],
            'statusHistory.*.period_start' => 'required|date',
            'statusHistory.*.period_end' => 'nullable|date',
        ];
    }

    private function classHistoryDataRules(): array
    {
        return [
            'classHistory.*.class' => ['required', Rule::in(Encounter::CLASS_CODE)],
            'classHistory.*.period_start' => 'required|date',
            'classHistory.*.period_end' => 'nullable|date',
        ];
    }

    private function participantDataRules(): array
    {
        return [
            'participant.*.type' => 'required|string|max:10',
            'participant.*.individual' => 'required|string',
        ];
    }

    private function reasonDataRules(): array
    {
        return [
            'reason.*.code' => 'nullable|integer|gte:0',
            'reason.*.reference' => 'nullable|string',
        ];
    }

    private function diagnosisDataRules(): array
    {
        return [
            'diagnosis.*.condition_reference' => 'required|string',
            'diagnosis.*.condition_display' => 'required|string',
            'diagnosis.*.use' => ['nullable', Rule::in(EncounterDiagnosis::USE_CODE)],
            'diagnosis.*.rank' => 'nullable|integer',
        ];
    }

    private function hospitalizationDataRules(): array
    {
        return [
            'hospitalization.*.hospitalization_data.preadmission_identifier_system' => 'nullable|string',
            'hospitalization.*.hospitalization_data.preadmission_identifier_use' => ['nullable', Rule::in(Constants::IDENTIFIER_USE)],
            'hospitalization.*.hospitalization_data.preadmission_identifier_value' => 'nullable|string',
            'hospitalization.*.hospitalization_data.origin' => 'nullable|string',
            'hospitalization.*.hospitalization_data.admit_source' => 'nullable|string',
            'hospitalization.*.hospitalization_data.re_admission' => ['nullable', Rule::in(EncounterHospitalization::READMISSION_CODE)],
            'hospitalization.*.hospitalization_data.destination' => 'nullable|string',
            'hospitalization.*.hospitalization_data.discharge_disposition' => 'nullable|string',
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

            'statusHistory.*.status.required' => 'Data status kunjungan historis harus diisi',
            'statusHistory.*.period_start.required' => 'Data historis status waktu mulainya kunjungan harus diisi',

            'classHistory.*.class.required' => 'Data jenis kunjungan historis harus diisi',
            'classHistory.*.period_start.required' => 'Data historis kelas waktu mulainya kunjungan harus diisi',

            'participant.*.type.required' => 'Data tipe tenaga kesehatan yang bertugas harus diisi',
            'participant.*.individual.required' => 'Data ID tenaga kesehatan yang bertugas harus diisi',

            'diagnosis.*.condition_reference.required' => 'Data ID keluhan pasien harus diisi',
            'diagnosis.*.condition_rdisplay.required' => 'Data tertulis keluhan pasien harus diisi',

            'hospitalization.*.diet.*.code.required' => 'Data kode pantangan konsumsi pasien harus diisi',

            'hospitalization.*.specialArrangement.*.code.required' => 'Data kode kebutuhan khusus pasien harus diisi',

            //Untuk Rule::in
            'encounter.status.in' => 'Harus termasuk "planned", "arrived", "triaged", "in-progress", "onleave", "finished", "cancelled", "entered-in-error", atau "unknown"',

            'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

            'statusHistory.*.status.in' => 'Harus termasuk "planned", "arrived", "triaged", "in-progress", "onleave", "finished", "cancelled", "entered-in-error", atau "unknown"',

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

            'classHistory.*.class.max' => 'Data jenis kunjungan historis maksimal 6 karakter',

            'participant.*.type.max' => 'Data tipe tenaga kesehatan yang bertugas maksimal 10 karakter'
        ];
    }
}
