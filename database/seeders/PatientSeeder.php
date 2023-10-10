<?php

namespace Database\Seeders;

use App\Models\PatientContactTelecom;
use App\Models\GeneralPractitioner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Resource;
use App\Models\Patient;
use App\Models\PatientAddress;
use App\Models\PatientContact;
use App\Models\PatientIdentifier;
use App\Models\PatientPhoto;
use App\Models\PatientTelecom;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Resource::join('resource_content', function ($join) {
            $join->on('resource.res_id', '=', 'resource_content.res_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Patient');
        })->get();

        $practitioners = Resource::join('resource_forced_id', function ($join) {
            $join->on('resource.res_id', '=', 'resource_forced_id.res_id')
                ->where('resource.res_type', '=', 'Practitioner');
        })->get();

        foreach ($patients as $p) {
            $resContent = json_decode($p->res_text, true);
            $nameData = getName($resContent);
            $gender = getGender($resContent) == null ? 'unknown' : getGender($resContent);
            $birthDate = getBirthDate($resContent) == null ? '1900-01-01' : getBirthDate($resContent);
            $extension = getExtension($resContent);
            $identifiers = getIdentifier($resContent);
            $telecoms = getTelecom($resContent);
            $address = getAddress($resContent);
            $photo = Storage::disk('public')->files('images');
            $contact = getContact($resContent);
            $generalPractitioners = getGeneralPractitioner($resContent);

            $patient = Patient::create(
                [
                    'res_id' => $p->res_id,
                    'name' => getFullName($nameData),
                    'prefix' => getPrefix($nameData),
                    'suffix' => getSuffix($nameData),
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                    'birth_place' => getBirthPlace($extension),
                    'deceased' => getDeceased($resContent),
                    'marital_status' => getMaritalStatus($resContent),
                    'multiple_birth' => getMultipleBirth($resContent),
                    'language' => getLanguage($resContent)
                ]
            );

            if (is_array($identifiers) || is_object($identifiers)) {
                foreach ($identifiers as $identifier) {
                    $identifierDetails = parseIdentifier($identifier);
                    PatientIdentifier::create(
                        [
                            'patient_id' => $patient->id,
                            'system' => $identifierDetails['system'],
                            'use' => $identifierDetails['use'],
                            'value' => $identifierDetails['value']
                        ]
                    );
                }
            }

            if (is_array($telecoms) || is_object($telecoms)) {
                foreach ($telecoms as $telecom) {
                    $telecomDetails = getTelecomDetails($telecom);
                    PatientTelecom::create(
                        [
                            'patient_id' => $patient->id,
                            'system' => $telecomDetails['system'],
                            'use' => $telecomDetails['use'],
                            'value' => $telecomDetails['value']
                        ]
                    );
                }
            }

            if (is_array($address) || is_object($address)) {
                foreach ($address as $a) {
                    $addressDetails = getAddressDetails($a);
                    PatientAddress::create(
                        [
                            'patient_id' => $patient->id,
                            'use' => $addressDetails['use'],
                            'line' => $addressDetails['line'],
                            'country' => $addressDetails['country'],
                            'postal_code' => $addressDetails['postalCode'],
                            'province' => $addressDetails['province'],
                            'city' => $addressDetails['city'],
                            'district' => $addressDetails['district'],
                            'village' => $addressDetails['village'],
                            'rw' => $addressDetails['rw'],
                            'rt' => $addressDetails['rt']
                        ]
                    );
                }
            }


            PatientPhoto::create(
                [
                    'patient_id' => $patient->id,
                    'title' => "Foto pasien",
                    'url' => $photo[0],
                    'creation' => Carbon::now()
                ]
            );

            if (is_array($contact) || is_object($contact)) {
                foreach ($contact as $c) {
                    $contactDetails = getContactDetails($c);
                    $addressDetails = getAddressDetails($address);
                    $patientContact = PatientContact::create(
                        [
                            'patient_id' => $patient->id,
                            'relationship' => $contactDetails['relationship'],
                            'name' => $contactDetails['name'],
                            'prefix' => $contactDetails['prefix'],
                            'suffix' => $contactDetails['suffix'],
                            'gender' => $contactDetails['gender'],
                            'address_use' => $addressDetails['use'],
                            'address_line' => $addressDetails['line'],
                            'country' => $addressDetails['country'],
                            'postal_code' => $addressDetails['postalCode'],
                            'province' => $addressDetails['province'],
                            'city' => $addressDetails['city'],
                            'district' => $addressDetails['district'],
                            'village' => $addressDetails['village'],
                            'rw' => $addressDetails['rw'],
                            'rt' => $addressDetails['rt']
                        ]
                    );

                    if (is_array($contactDetails['telecom']) || is_object($contactDetails['telecom'])) {
                        foreach ($contactDetails['telecom'] as $telecom) {
                            $contactTelecomDetails = getTelecomDetails($telecom);
                            PatientContactTelecom::create(
                                [
                                    'contact_id' => $patientContact->id,
                                    'system' => $contactTelecomDetails['system'],
                                    'use' => $contactTelecomDetails['use'],
                                    'value' => $contactTelecomDetails['value']
                                ]
                            );
                        }
                    }
                }
            }


            if (is_array($generalPractitioners) || is_object($generalPractitioners)) {
                foreach ($generalPractitioners as $gp) {
                    $ref = $gp['reference'];
                    foreach ($practitioners as $prac) {
                        if ($prac->forced_id === $ref) {
                            $prac_id = $prac->id;
                            break;
                        } else {
                            $prac_id = 0;
                        }
                    }
                    GeneralPractitioner::create(
                        [
                            'patient_id' => $patient->id,
                            'practitioner_id' => $prac_id,
                            'reference' => $ref
                        ]
                    );
                }
            }
        }
    }
}
