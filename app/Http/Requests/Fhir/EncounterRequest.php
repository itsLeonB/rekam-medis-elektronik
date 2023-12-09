<?php

namespace App\Http\Requests\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Http\Requests\FhirRequest;
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
            $this->baseDataRules('encounter.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->statusHistoryDataRules(),
            $this->classHistoryDataRules(),
            $this->participantDataRules(),
            $this->diagnosisDataRules(),
            $this->locationDataRules('location.*.'),
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
            'diagnosis' => 'nullable|array',
            'location' => 'required|array',
        ];
    }

    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'status' => ['required', Rule::in(Valuesets::EncounterStatus['code'])],
                $prefix . 'class' => ['required', Rule::in(Valuesets::EncounterClass['code'])],
                $prefix . 'type' => 'nullable|array',
                $prefix . 'type.*' => ['required', Rule::in(Codesystems::EncounterType['code'])],
                $prefix . 'service_type' => 'nullable|exists:codesystem_servicetype,code',
                $prefix . 'priority' => ['nullable', Rule::in(Valuesets::EncounterPriority['code'])],
                $prefix . 'subject' => 'required|string',
                $prefix . 'episode_of_care' => 'nullable|array',
                $prefix . 'episode_of_care.*' => 'required|string',
                $prefix . 'based_on' => 'nullable|array',
                $prefix . 'based_on.*' => 'required|string',
                $prefix . 'period_start' => 'required|date',
                $prefix . 'period_end' => 'nullable|date',
                $prefix . 'reason_code' => 'nullable|array',
                $prefix . 'reason_code.*' => 'required|integer|gte:0',
                $prefix . 'reason_reference' => 'nullable|array',
                $prefix . 'reason_reference.*' => 'required|string',
                $prefix . 'account' => 'nullable|array',
                $prefix . 'account.*' => 'required|string',
                $prefix . 'hospitalization_preadmission_identifier_system' => 'nullable|string',
                $prefix . 'hospitalization_preadmission_identifier_use' => ['nullable', Rule::in(Codesystems::IdentifierUse['code'])],
                $prefix . 'hospitalization_preadmission_identifier_value' => 'nullable|string',
                $prefix . 'hospitalization_origin' => 'nullable|string',
                $prefix . 'hospitalization_admit_source' => ['nullable', Rule::in(Codesystems::AdmitSource['code'])],
                $prefix . 'hospitalization_re_admission' => ['nullable', Rule::in(Codesystems::v20092['code'])],
                $prefix . 'hospitalization_diet_preference' => 'nullable|array',
                $prefix . 'hospitalization_diet_preference.*' => ['required', Rule::in(Codesystems::Diet['code'])],
                $prefix . 'hospitalization_special_arrangement' => 'nullable|array',
                $prefix . 'hospitalization_special_arrangement.*' => ['required', Rule::in(Codesystems::SpecialArrangements['code'])],
                $prefix . 'hospitalization_destination' => 'nullable|string',
                $prefix . 'hospitalization_discharge_disposition' => ['nullable', Rule::in(Codesystems::DischargeDisposition['code'])],
                $prefix . 'service_provider' => 'required|string',
                $prefix . 'part_of' => 'nullable|string',
            ],
            $this->getDurationDataRules($prefix . 'length_'),
        );
    }

    private function statusHistoryDataRules(): array
    {
        return [
            'statusHistory.*.status' => ['required', Rule::in(Valuesets::EncounterStatus['code'])],
            'statusHistory.*.period_start' => 'required|date',
            'statusHistory.*.period_end' => 'nullable|date',
        ];
    }

    private function classHistoryDataRules(): array
    {
        return [
            'classHistory.*.class' => ['required', Rule::in(Valuesets::EncounterClass['code'])],
            'classHistory.*.period_start' => 'required|date',
            'classHistory.*.period_end' => 'nullable|date',
        ];
    }


    private function participantDataRules(): array
    {
        return [
            'participant.*.type' => 'nullable|array',
            'participant.*.type.*' => ['required', Rule::in(Valuesets::EncounterParticipantType['code'])],
            'participant.*.individual' => 'nullable|string',
        ];
    }


    private function diagnosisDataRules(): array
    {
        return [
            'diagnosis.*.condition' => 'required|string',
            'diagnosis.*.use' => ['nullable', Rule::in(Codesystems::DiagnosisRole['code'])],
            'diagnosis.*.rank' => 'nullable|integer',
        ];
    }


    private function locationDataRules($prefix): array
    {
        return [
            $prefix . 'location' => 'required|string',
            $prefix . 'service_class' => ['nullable', Rule::in(Valuesets::LocationServiceClass['code'])],
            $prefix . 'upgrade_class' => ['nullable', Rule::in(Codesystems::LocationUpgradeClass['code'])],
        ];
    }



    // public function messages(): array
    // {
    //     // create the corresponding validation error message according to the rules above
    //     return [
    //         //Untuk required
    //         $prefix . 'status.required' => 'Status kunjungan pasien harus diisi',
    //         $prefix . 'class.required' => 'Jenis kunjungan pasien harus diisi',
    //         $prefix . 'subject.required' => 'Data pasien harus diisi',
    //         $prefix . 'period_start.required' => 'Data waktu mulainya kunjungan pasien harus diisi',
    //         $prefix . 'location.required' => 'Data lokasi kunjungan pasien harus diisi',
    //         $prefix . 'service_provider.required' => 'Data fasyankes yang dikunjungi pasien harus diisi',

    //         'identifier.*.system.required' => 'Identifier system harus diisi',
    //         'identifier.*.use.required' => 'Identifier use harus diisi',
    //         'identifier.*.value.required' => 'Identifier value harus diisi',

    //         'statusHistory.*.status.required' => 'Data status kunjungan historis harus diisi',
    //         'statusHistory.*.period_start.required' => 'Data historis status waktu mulainya kunjungan harus diisi',

    //         'classHistory.*.class.required' => 'Data jenis kunjungan historis harus diisi',
    //         'classHistory.*.period_start.required' => 'Data historis kelas waktu mulainya kunjungan harus diisi',

    //         'participant.*.type.required' => 'Data tipe tenaga kesehatan yang bertugas harus diisi',
    //         'participant.*.individual.required' => 'Data ID tenaga kesehatan yang bertugas harus diisi',

    //         'diagnosis.*.condition_reference.required' => 'Data ID keluhan pasien harus diisi',
    //         'diagnosis.*.condition_rdisplay.required' => 'Data tertulis keluhan pasien harus diisi',

    //         'hospitalization.*.diet.*.code.required' => 'Data kode pantangan konsumsi pasien harus diisi',

    //         'hospitalization.*.specialArrangement.*.code.required' => 'Data kode kebutuhan khusus pasien harus diisi',

    //         //Untuk Rule::in
    //         $prefix . 'status.in' => 'Harus termasuk "planned", "arrived", "triaged", "in-progress", "onleave", "finished", "cancelled", "entered-in-error", atau "unknown"',

    //         'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

    //         'statusHistory.*.status.in' => 'Harus termasuk "planned", "arrived", "triaged", "in-progress", "onleave", "finished", "cancelled", "entered-in-error", atau "unknown"',

    //         //Untuk gte
    //         $prefix . 'service_type.gte' => 'Nilai tipe layanan tidak boleh negatif',

    //         'reason.*.code.gte' => 'Nilai kode alasan kunjungan tidak boleh negatif',

    //         //Untuk integer
    //         $prefix . 'service_type.integer' => 'Data tipe layanan harus berbentuk angka',

    //         'reason.*.code.integer' => 'Data kode alasan kunjungan harus berbentuk angka',

    //         'diagnosis.*.rank.integer' => 'Data peringkat diagnosis harus berbentuk angka',

    //         //Untuk max
    //         $prefix . 'class.max' => 'Data jenis kunjungan maksimal 6 karakter',
    //         $prefix . 'priority.max' => 'Data prioritas kunjungan maksimal 3 karakter',

    //         'classHistory.*.class.max' => 'Data jenis kunjungan historis maksimal 6 karakter',

    //         'participant.*.type.max' => 'Data tipe tenaga kesehatan yang bertugas maksimal 10 karakter'
    //     ];
    // }
}
