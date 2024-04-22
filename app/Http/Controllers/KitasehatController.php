<?php

namespace App\Http\Controllers;

use App\Fhir\Processor;
use App\Http\Requests\Fhir\Search\PatientSearchRequest;
use App\Models\Fhir\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitasehatController extends Controller
{
    public function retrievePatient(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            $nik = data_get($data, 'patient.nik');

            $satusehat = new SatusehatController();

            $searchResponse = $satusehat->searchPatient(new PatientSearchRequest([
                'identifier' => "https://fhir.kemkes.go.id/id/nik|" . $nik
            ]));

            $searchResponse = json_decode($searchResponse->getBody()->getContents(), true);

            if (data_get($searchResponse, 'total') == 0) {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Pasien tidak ditemukan'
                ], 404);
            }

            $processor = new Processor();

            $resource = data_get($searchResponse, 'entry.0.resource');

            $patientData = $processor->generatePatient($resource);
            $res = Resource::create([
                'res_type' => 'Patient',
                'satusehat_id' => $resource['id'] ?? null,
            ]);
            $patient = $processor->savePatient($res, $patientData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'pasien' => $patient
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
