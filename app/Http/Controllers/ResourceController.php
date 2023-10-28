<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\GeneralPractitioner;
use App\Models\Patient;
use App\Models\PatientContact;
use App\Models\PatientContactTelecom;
use App\Models\PatientIdentifier;
use App\Models\PatientTelecom;
use App\Models\Resource;
use App\Models\ResourceContent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    public function getPatient($satusehatId)
    {
        return (new PatientResource(
            Resource::where('satusehat_id', $satusehatId)->firstOrFail()
        ))
            ->response()
            ->setStatusCode(200);
    }

    public function postPatient(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        DB::beginTransaction();

        try {
            $resource = Resource::create([
                'res_type' => 'Patient',
                'res_ver' => 1,
            ]);

            ResourceContent::create([
                'res_ver' => 1,
                'res_text' => $content,
            ]);

            $patient = Patient::create($this->readPatientData($resource, $content));
            $patientKey = ['patient_id' => $patient->id];
            parseAndCreate(PatientIdentifier::class, returnAttribute($content, ['identifier']), 'returnIdentifier', $patientKey);
            parseAndCreate(PatientTelecom::class, returnAttribute($content, ['telecom']), 'returnTelecom', $patientKey);
            parseAndCreate(PatientAddress::class, returnAttribute($content, ['address']), 'returnAddress', $patientKey);
            parseAndCreate(GeneralPractitioner::class, returnAttribute($content, ['generalPractitioner']), 'returnReference', $patientKey);

            $contact = returnAttribute($content, ['contact']);

            if (is_array($contact) || is_object($contact)) {
                foreach ($contact as $c) {
                    $contactData = returnPatientContact($c);
                    $patientContact = PatientContact::create(array_merge($contactData, $patientKey));
                    $contactKey = ['contact_id' => $patientContact->id];
                    $contactTelecom = returnAttribute($c, ['telecom']);
                    parseAndCreate(PatientContactTelecom::class, $contactTelecom, 'returnTelecom', $contactKey);
                }
            }

            DB::commit();

            return (new PatientResource($content))
            ->response()
            ->setStatusCode(201);
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    private function readPatientData($resource, $content)
    {
        return [
            'resource_id' => $resource->id,
            'active' => returnAttribute($content, ['active']),
            'name' => getFullName(returnAttribute($content, ['name'])),
            'prefix' => returnAttribute($content, ['name', 0, 'prefix']),
            'suffix' => returnAttribute($content, ['name', 0, 'suffix']),
            'gender' => returnAttribute($content, ['gender'], 'unknown'),
            'birth_date' => parseDate(returnAttribute($content, ['birthDate'])),
            'birthPlace' => getBirthPlace(returnAttribute($content, ['extension'])),
            'deceased' => returnVariableAttribute($content, 'deceased', ['Boolean', 'DateTime']),
            'marital_status' => returnAttribute($content, ['maritalStatus', 'coding', 0, 'code']),
            'multiple_birth' => returnVariableAttribute($content, 'multipleBirth', ['Boolean', 'Integer']),
            'language' => returnAttribute($content, ['communication', 0, 'language', 'coding', 0, 'code']),
        ];
    }
}
