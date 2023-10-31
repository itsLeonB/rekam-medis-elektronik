<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
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
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function postPatient(PatientRequest $request)
    {
        $body = json_decode($request->getContent(), true);
        $body = removeEmptyValues($body);

        DB::beginTransaction();
        try {

            $resource = Resource::create([
                'res_type' => 'Patient',
                'res_ver' => 1,
            ]);

            $resourceKey = ['resource_id' => $resource->id];

            $patient = Patient::create(array_merge($resourceKey, $body['patient']));

            $patientKey = ['patient_id' => $patient->id];

            $this->createInstances(PatientIdentifier::class, $patientKey, $body, 'identifier');
            $this->createInstances(PatientTelecom::class, $patientKey, $body, 'telecom');
            $this->createInstances(PatientAddress::class, $patientKey, $body, 'address');
            $this->createInstances(GeneralPractitioner::class, $patientKey, $body, 'general_practitioner');
            $this->createInstances(PatientContact::class, $patientKey, $body, 'contact_data', [
                [
                    'model' => PatientContactTelecom::class,
                    'key' => 'contact_id',
                    'bodyKey' => 'telecom'
                ]
            ]);

            $resourceData = new PatientResource($resource);
            $resourceText = json_encode($resourceData);

            ResourceContent::create([
                'resource_id' => $resource->id,
                'res_ver' => 1,
                'res_text' => $resourceText,
            ]);

            DB::commit();
            return response()->json($resource->patient->first(), 201);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database error: ' . $e->getMessage());
            return response()->json(['error' => 'Database error dalam input data pasien baru.'], 500);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error dalam input data pasien baru.'], 500);
        }
    }
}
