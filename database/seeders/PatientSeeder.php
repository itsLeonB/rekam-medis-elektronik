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
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Patient');
        })->get();

        foreach ($patients as $p) {
            $resContent = json_decode($p->res_text, true);
            $active = getActive($resContent);
            $nameData = getName($resContent);
            $gender = getGender($resContent) == null ? 'unknown' : getGender($resContent);
            $birthDate = getBirthDate($resContent) == null ? '1900-01-01' : getBirthDate($resContent);
            $extension = getExtension($resContent);
            $identifiers = returnAttribute($resContent, ['identifier'], null);
            $telecoms = returnAttribute($resContent, ['telecom'], null);
            $address = returnAttribute($resContent, ['address'], null);
            $photo = Storage::disk('public')->files('images');
            $contact = returnAttribute($resContent, ['contact'], null);
            $generalPractitioners = getGeneralPractitioner($resContent);

            $patient = Patient::create(
                [
                    'resource_id' => $p->resource_id,
                    'active' => $active,
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

            $foreignKey = ['patient_id' => $patient->id];

            parseAndCreate(PatientIdentifier::class, $identifiers, 'returnIdentifier', $foreignKey);
            parseAndCreate(PatientTelecom::class, $telecoms, 'returnTelecom', $foreignKey);
            parseAndCreate(PatientAddress::class, $address, 'returnAddress', $foreignKey);

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
                    $contactData = returnPatientContact($c);
                    $contactTelecom = returnAttribute($c, ['telecom'], null);
                    $patientContact = PatientContact::create(array_merge($contactData, $foreignKey));
                    $contactFk = ['contact_id' => $patientContact->id];
                    parseAndCreate(PatientContactTelecom::class, $contactTelecom, 'returnTelecom', $contactFk);
                }
            }

            if (is_array($generalPractitioners) || is_object($generalPractitioners)) {
                foreach ($generalPractitioners as $gp) {
                    $ref = $gp['reference'];
                    GeneralPractitioner::create(
                        [
                            'patient_id' => $patient->id,
                            'reference' => $ref
                        ]
                    );
                }
            }
        }
    }
}
