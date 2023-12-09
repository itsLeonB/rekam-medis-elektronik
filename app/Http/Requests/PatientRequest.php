<?php

namespace App\Http\Requests;

use App\Models\Patient;
use App\Models\PatientCommunication;
use App\Models\PatientContact;
use App\Models\PatientLink;
use App\Models\PatientName;
use Illuminate\Validation\Rule;

class PatientRequest extends FhirRequest
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
            $this->baseDataRules('patient.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->nameDataRules('name.*.'),
            $this->getTelecomDataRules('telecom.*.'),
            $this->getAddressDataRules('address.*.'),
            $this->contactDataRules('contact.*.'),
            $this->communicationDataRules('communication.*.'),
            $this->linkDataRules('link.*.')
        );
    }

    public function baseAttributeRules(): array
    {
        return [
            'patient' => 'required|array',
            'identifier' => 'required|array',
            'name' => 'required|array',
            'telecom' => 'nullable|array',
            'address' => 'nullable|array',
            'photo' => 'nullable|array',
            'contact' => 'nullable|array',
            'communication' => 'nullable|array',
            'link' => 'nullable|array'
        ];
    }

    public function baseDataRules($prefix): array
    {
        return [
            $prefix . 'active' => 'required|boolean',
            $prefix . 'gender' => ['required', Rule::in(Patient::GENDER['binding']['valueset'])],
            $prefix . 'birth_date' => 'nullable|date',
            $prefix . 'deceased' => 'nullable|array',
            $prefix . 'deceased.deceasedBoolean' => 'nullable|boolean',
            $prefix . 'deceased.deceasedDateTime' => 'nullable|date',
            $prefix . 'marital_status' => ['nullable', Rule::in(Patient::MARITAL_STATUS['binding']['valueset']['code'])],
            $prefix . 'multiple_birth' => 'nullable|array',
            $prefix . 'multiple_birth.multipleBirthBoolean' => 'nullable|boolean',
            $prefix . 'multiple_birth.multipleBirthInteger' => 'nullable|integer',
            $prefix . 'general_practitioner' => 'nullable|array',
            $prefix . 'general_practitioner.*' => 'required|string',
            $prefix . 'managing_organization' => 'nullable|string',
            $prefix . 'birth_city' => 'nullable|string',
            $prefix . 'birth_country' => ['nullable', Rule::exists(Patient::BIRTH_COUNTRY['binding']['valueset']['table'], 'code')],
        ];
    }


    private function nameDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'use' => ['required', 'string', Rule::in(PatientName::USE['binding']['valueset']['code'])],
            $prefix . 'text' => 'nullable|string',
            $prefix . 'family' => 'required|string',
            $prefix . 'given' => 'required|array',
            $prefix . 'given.*' => 'required|string',
            $prefix . 'prefix' => 'nullable|array',
            $prefix . 'prefix.*' => 'nullable|string',
            $prefix . 'suffix' => 'nullable|array',
            $prefix . 'suffix.*' => 'nullable|string',
            $prefix . 'period_start' => 'nullable|date',
            $prefix . 'period_end' => 'nullable|date',
        ];
    }


    public function contactDataRules(string $prefix = null): array
    {
        $address = $this->getAddressDataRules($prefix . 'contact_data.');
        $address[$prefix . 'contact_data.address_use'] = $address[$prefix . 'contact_data.use'];
        $address[$prefix . 'contact_data.address_type'] = $address[$prefix . 'contact_data.type'];
        $address[$prefix . 'contact_data.address_line'] = $address[$prefix . 'contact_data.line'];
        unset($address[$prefix . 'contact_data.use']);
        unset($address[$prefix . 'contact_data.type']);
        unset($address[$prefix . 'contact_data.line']);
        return array_merge(
            [
                $prefix . 'contact_data.relationship' => 'nullable|array',
                $prefix . 'contact_data.relationship.*' => ['required', 'string', Rule::in(PatientContact::RELATIONSHIP['binding']['valueset']['code'])],
                $prefix . 'contact_data.name_text' => 'nullable|string',
                $prefix . 'contact_data.name_family' => 'nullable|string',
                $prefix . 'contact_data.name_given' => 'nullable|array',
                $prefix . 'contact_data.name_given.*' => 'sometimes|string',
                $prefix . 'contact_data.name_prefix' => 'nullable|array',
                $prefix . 'contact_data.name_prefix.*' => 'sometimes|string',
                $prefix . 'contact_data.name_suffix' => 'nullable|array',
                $prefix . 'contact_data.name_suffix.*' => 'sometimes|string',
                $prefix . 'contact_data.gender' => ['required', 'string', Rule::in(PatientContact::GENDER['binding']['valueset'])],
                $prefix . 'contact_data.organization' => 'nullable|string',
                $prefix . 'contact_data.period_start' => 'nullable|date',
                $prefix . 'contact_data.period_end' => 'nullable|date',
            ],
            $address,
            $this->getTelecomDataRules($prefix . 'telecom.*.')
        );
    }


    public function communicationDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'language' => ['required', Rule::exists(PatientCommunication::LANGUAGE['binding']['valueset']['table'], 'code')],
            $prefix . 'preferred' => 'nullable|boolean',
        ];
    }


    public function linkDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'other' => 'required|string',
            $prefix . 'type' => ['required', Rule::in(PatientLink::TYPE['binding']['valueset']['code'])],
        ];
    }

    // public function messages(): array
    // {
    //     // create the corresponding validation error message according to the rules above
    //     return [
    //         //Untuk required
    //         $prefix . 'active.required' => 'Harus dipilih.',
    //         $prefix . 'name.required' => 'Nama harus diisi.',
    //         $prefix . 'gender.required' => 'Jenis kelamin harus dipilih',

    //         'identifier.*.system.required' => 'Identifier system harus diisi',
    //         'identifier.*.use.required' => 'Identifier use harus diisi',
    //         'identifier.*.value.required' => 'Identifier value harus diisi',

    //         'telecom.*.system.required' => 'Sistem telekomunikasi pasien harus diisi',
    //         'telecom.*.use.required' => 'Kegnuaan telekomunikasi pasien harus diisi',
    //         'telecom.*.value.required' => 'Keterangan nilai telekomunikasi pasien harus diisi',

    //         'address.*.use.required' => 'Kegunaan alamat pasien harus diisi',
    //         'address.*.line.required' => 'Alamat pasien harus diisi',
    //         'address.*.country.required' => 'Asal negara pasien harus diisi',
    //         'address.*.postal_code.required' => 'Kode pos pasien harus diisi',
    //         'address.*.province.required' => 'Asal provinsi pasien harus diisi',
    //         'address.*.city.required' => 'Asal kota pasien harus diisi',
    //         'address.*.district.required' => 'Asal kecamatan pasien harus diisi',
    //         'address.*.village.required' => 'Asal kelurahan/desa pasien harus diisi',
    //         'address.*.rt.required' => 'Asal RT pasien harus diisi',
    //         'address.*.rw.required' => 'Asal RW pasien harus diisi',

    //         $prefix . 'contact_data.relationship.required' => 'Jenis kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.name.required' => 'Nama kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.gender.required' => 'Jenis kelamin kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.address_use.required' => 'Kegunaan alamat kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.address_line.required' => 'Alamat kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.country.required' => 'Asal negara kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.postal_code.required' => 'Kode pos kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.province.required' => 'Asal provinsi kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.city.required' => 'Asal kota kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.district.required' => 'Asal kecamatan kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.village.required' => 'Asal kelurahan/desa kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.rt.required' => 'Asal RT kontak darurat pasien harus diisi',
    //         $prefix . 'contact_data.rw.required' => 'Asal RW kontak darurat pasien harus diisi',

    //         $prefix . 'telecom.*.system.required' => 'Sistem telekomunikasi kontak darurat pasien harus diisi',
    //         $prefix . 'telecom.*.use.required' => 'Kegunaan telekomunikasi kontak darurat pasien harus diisi',
    //         $prefix . 'telecom.*.value.required' => 'Keterangan nilai telekomunikasi kontak darurat pasien harus diisi',

    //         'generalPractitioner.*.reference.required' => 'Referensi general practitioner harus diisi',

    //         // Untuk Rule::in
    //         $prefix . 'gender.in' => 'Harus termasuk "male", "female", "other", atau "unknown"',

    //         'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

    //         'telecom.*.system.in' => 'Harus termasuk "phone", "fax", "email", "pager", "url", "sms", atau "other"',
    //         'telecom.*.use.in' => 'Harus termasuk "home", "work", "temp", "old", atau "mobile"',

    //         'address.*.use.in' => 'Harus termasuk "home", "work", "temp", "old", atau "billing"',

    //         $prefix . 'contact_data.relationship.in' => 'Harus termasuk "BP", "CP", "EP", "PR", "E", "C", "F", "I", "N", "S", atau "U"',
    //         $prefix . 'contact_data.gender.in' => 'Harus termasuk "male", "female", "other", atau "unknown"',
    //         $prefix . 'contact_data.address_use.in' => 'Harus termasuk "home", "work", "temp", "old", atau "billing"',

    //         $prefix . 'telecom.*.system.in' => 'Harus termasuk "phone", "fax", "email", "pager", "url", "sms", atau "other"',
    //         $prefix . 'telecom.*.use.in' => 'Harus termasuk "home", "work", "temp", "old", atau "mobile"',

    //         //Untuk gte
    //         'address.*.province.gte' => 'Nilai provinsi asal pasien tidak boleh negatif',
    //         'address.*.city.gte' => 'Nilai kota asal pasien tidak boleh negatif',
    //         'address.*.district.gte' => 'Nilai kecamatan asal pasien tidak boleh negatif',
    //         'address.*.village.gte' => 'Nilai kelurahan/desa asal pasien tidak boleh negatif',
    //         'address.*.rt.gte' => 'Nilai RT asal pasien tidak boleh negatif',
    //         'address.*.rw.gte' => 'Nilai RW asal pasien tidak boleh negatif',

    //         $prefix . 'contact_data.province.gte' => 'Nilai provinsi asal kontak darurat pasien tidak boleh negatif',
    //         $prefix . 'contact_data.city.gte' => 'Nilai kota asal kontak darurat pasien tidak boleh negatif',
    //         $prefix . 'contact_data.district.gte' => 'Nilai kecamatan asal kontak darurat pasien tidak boleh negatif',
    //         $prefix . 'contact_data.village.gte' => 'Nilai kelurahan/desa asal kontak darurat pasien tidak boleh negatif',
    //         $prefix . 'contact_data.rt.gte' => 'Nilai RT asal kontak darurat pasien tidak boleh negatif',
    //         $prefix . 'contact_data.rw.gte' => 'Nilai RW asal kontak darurat pasien tidak boleh negatif',

    //         //Untuk digits
    //         'address.*.province.digits' => 'Nilai provnsi asal pasien harus terdiri dari 2 digit angka',
    //         'address.*.city.digits' => 'Nilai kota asal pasien harus terdiri dari 4 digit angka',
    //         'address.*.district.digits' => 'Nilai kecamatan asal pasien harus terdiri dari 6 digit angka',
    //         'address.*.village.digits' => 'Nilai kelurahan/desa asal pasien harus terdiri dari 10 digit angka',

    //         $prefix . 'contact_data.province.digits' => 'Nilai provinsi asal kontak darurat pasien harus terdiri dari 2 digit angka',
    //         $prefix . 'contact_data.city.digits' => 'Nilai kota asal kontak darurat pasien harus terdiri dari 4 digit angka',
    //         $prefix . 'contact_data.district.digits' => 'Nilai kecamatan asal kontak darurat pasien harus terdiri dari 6 digit angka',
    //         $prefix . 'contact_data.village.digits' => 'Nilai kelurahan/desa asal kontak darurat pasien harus terdiri dari 10 digit angka',

    //         //Untuk max_digits
    //         'address.*.rt.max_digits' => 'Nilai RT asal pasien harus terdiri dari maksimal 2 digit angka',
    //         'address.*.rw.max_digits' => 'Nilai RW asal pasien harus terdiri dari 2 digit angka',

    //         $prefix . 'contact_data.rt.max_digits' => 'Nilai RT asal kontak darurat pasien harus terdiri dari 2 digit angka',
    //         $prefix . 'contact_data.rw.max_digits' => 'Nilai RW asal kontak darurat pasien harus terdiri dari 2 digit angka',

    //         //Untuk integer
    //         'address.*.province.integer' => 'Nilai provinsi asal pasien harus berbentuk angka',
    //         'address.*.city.integer' => 'Nilai kota asal pasien harus berbentuk angka',
    //         'address.*.district.integer' => 'Nilai asal kecamatan pasien harus berbentuk angka',
    //         'address.*.village.integer' => 'Nilai asal kelurahan/desa pasien harus berbentuk angka',
    //         'address.*.rt.integer' => 'Nilai asal RT pasien harus berbentuk angka',
    //         'address.*.rw.integer' => 'Nilai asal RW pasien harus berbentuk angka',

    //         $prefix . 'contact_data.province.integer' => 'Nilai provinsi asal kontak darurat pasien harus berbentuk angka',
    //         $prefix . 'contact_data.city.integer' => 'Nilai kota asal kontak darurat pasien harus berbentuk angka',
    //         $prefix . 'contact_data.district.integer' => 'Nilai asal kecamatan kontak darurat pasien harus berbentuk angka',
    //         $prefix . 'contact_data.village.integer' => 'Nilai asal kelurahan/desa kontak darurat pasien harus berbentuk angka',
    //         $prefix . 'contact_data.rt.integer' => 'Nilai asal RT kontak darurat pasien harus berbentuk angka',
    //         $prefix . 'contact_data.rw.integer' => 'Nilai asal RW kontak darurat pasien harus berbentuk angka',
    //     ];
    // }
}
