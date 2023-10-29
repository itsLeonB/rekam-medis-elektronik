<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\GeneralPractitioner;
use App\Models\Patient;
use App\Models\PatientAddress;
use App\Models\PatientContact;
use App\Models\PatientContactTelecom;
use App\Models\PatientIdentifier;
use App\Models\PatientTelecom;
use App\Models\Resource;
use App\Models\ResourceContent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    public function getResource($res_type, $satusehat_id)
    {
        return response(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', $res_type]
        ])->firstOrFail()->patient->first(), 200);
    }

    public function postPatient(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        DB::beginTransaction();

        try {
            $resource = Resource::create([
                'satusehat_id' => 'P000000',
                'res_type' => 'Patient',
                'res_ver' => 1,
            ]);

            $resourceKey = ['resource_id' => $resource->id];

            $patient = Patient::create(array_merge($resourceKey, $body['patient']));

            $patientKey = ['patient_id' => $patient->id];

            foreach ($body['identifier'] as $i) {
                PatientIdentifier::create(array_merge($patientKey, $i));
            }

            foreach ($body['telecom'] as $t) {
                PatientTelecom::create(array_merge($patientKey, $t));
            }

            foreach ($body['address'] as $a) {
                PatientAddress::create(array_merge($patientKey, $a));
            }

            foreach ($body['general_practitioner'] as $gp) {
                GeneralPractitioner::create(array_merge($patientKey, $gp));
            }

            foreach ($body['contact'] as $c) {
                $contact = PatientContact::create(array_merge($patientKey, $c['contact_data']));

                $contactKey = ['contact_id' => $contact->id];

                foreach ($c['telecom'] as $ct) {
                    PatientContactTelecom::create(array_merge($contactKey, $ct));
                }
            }

            // $resourceData = new PatientResource($body);
            // $resourceText = json_encode($resourceData);

            // ResourceContent::create([
            //     'res_ver' => 1,
            //     'res_text' => $resourceText,
            // ]);

            DB::commit();

            return response($body, 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error dalam input data pasien baru: ' . $e->getMessage());
            return response()->json(['error' => 'Error dalam input data pasien baru.'], 500);
        }
    }
}
