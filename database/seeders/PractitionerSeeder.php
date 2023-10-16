<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\Practitioner;
use App\Models\PractitionerAddress;
use App\Models\PractitionerQualification;
use App\Models\PractitionerTelecom;
use Illuminate\Support\Facades\Storage;

class PractitionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $practitioners = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Practitioner');
        })->get();
        $photo = Storage::disk('public')->files('images');
        $count = 1;
        foreach ($practitioners as $p) {
            $resContent = json_decode($p->res_text, true);
            $active = getActive($resContent);
            $nameData = getName($resContent);
            $identifier = getIdentifier($resContent);
            $gender = getGender($resContent) == null ? 'unknown' : getGender($resContent);
            $birthDate = getBirthDate($resContent) == null ? '1900-01-01' : getBirthDate($resContent);
            $nik = getNik($identifier) == null ? 9999999999999999 : getNik($identifier);
            $ihs = getIhs($identifier) == null ? 'N10000000' : getIhs($identifier);
            $telecom = getTelecom($resContent);
            $address = getAddress($resContent);
            $qualifications = getQualifications($resContent);

            Practitioner::create(
                [
                    'resource_id' => $p->resource_id,
                    'nik' => $nik,
                    'ihs_number' => $ihs,
                    'active' => $active,
                    'name' => getFullName($nameData),
                    'prefix' => getPrefix($nameData),
                    'suffix' => getSuffix($nameData),
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                    'photo' => $photo[0],
                ]
            );

            if ($telecom != null) {
                foreach ($telecom as $t) {
                    $telecomDetails = getTelecomDetails($t);
                    PractitionerTelecom::create(
                        [
                            'practitioner_id' => $count,
                            'system' => $telecomDetails['system'],
                            'use' => $telecomDetails['use'],
                            'value' => $telecomDetails['value'],
                        ]
                    );
                }
            }

            if ($address != null) {
                foreach ($address as $a) {
                    $addressDetails = getAddressDetails($a);
                    PractitionerAddress::create(
                        [
                            'practitioner_id' => $count,
                            'use' => $addressDetails['use'],
                            'line' => $addressDetails['line'],
                            'postal_code' => $addressDetails['postalCode'],
                            'country' => $addressDetails['country'],
                            'rt' => $addressDetails['rt'],
                            'rw' => $addressDetails['rw'],
                            'village' => $addressDetails['village'],
                            'district' => $addressDetails['district'],
                            'city' => $addressDetails['city'],
                            'province' => $addressDetails['province'],
                        ]
                    );
                }
            }

            if ($qualifications != null) {
                foreach ($qualifications as $q) {
                    $qualificationDetails = getQualificationDetails($q);
                    // dd($qualificationDetails);
                    PractitionerQualification::create(
                        [
                            'practitioner_id' => $count,
                            'code' => $qualificationDetails['code'],
                            'code_system' => $qualificationDetails['system'],
                            'display' => $qualificationDetails['display'],
                            'identifier' => $qualificationDetails['identifier'],
                            'issuer' => $qualificationDetails['issuer'],
                            'period_start' => $qualificationDetails['periodStart'],
                            'period_end' => $qualificationDetails['periodEnd']
                        ]
                    );
                }
            }
            $count++;
        }
    }
}
