<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\AllergyIntolerance;
use App\Models\Fhir\ClinicalImpression;
use App\Models\Fhir\Composition;
use App\Models\Fhir\Condition;
use App\Models\Fhir\Encounter;
use App\Models\Fhir\MedicationDispense;
use App\Models\Fhir\MedicationRequest;
use App\Models\Fhir\MedicationStatement;
use App\Models\Fhir\Observation;
use App\Models\Fhir\Patient;
use App\Models\Fhir\Procedure;
use App\Models\Fhir\QuestionnaireResponse;
use App\Models\Fhir\Resource;
use App\Models\Fhir\ServiceRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    public function index()
    {
        $patients = Resource::join('patient', 'patient.resource_id', '=', 'resource.id')
            ->leftJoin('encounter', function ($join) {
                $join->on('encounter.subject', '=', DB::raw('CONCAT("Patient/", resource.satusehat_id)'))
                    ->whereColumn('encounter.subject', '=', DB::raw('CONCAT("Patient/", resource.satusehat_id)'))
                    ->orderByDesc('encounter.period_start')
                    ->limit(1);
            })
            ->leftJoin('patient_name', function ($join) {
                $join->on('patient_name.patient_id', '=', 'patient.id')
                    ->orderByDesc('patient_name.period_start')
                    ->limit(1);
            })
            ->leftJoin('patient_identifier', function ($join) {
                $join->on('patient_identifier.patient_id', '=', 'patient.id')
                    ->where('patient_identifier.system', '=', 'rme')
                    ->limit(1);
            })
            ->select(
                'patient.id',
                'patient_name.text',
                'patient_identifier.value',
                'encounter.class',
                'encounter.period_start'
            )
            ->distinct('patient.id')
            ->orderByDesc('encounter.period_start')
            ->paginate(15);


        // Return the result as JSON
        return response()->json(['patients' => $patients]);
    }


    public function show($patientId)
    {
        try {
            $patient = Patient::findOrFail($patientId);
            $patientSatusehatId = $patient->resource->satusehat_id;

            $data = [
                'patient' => $patient,
                'encounters' => [],
                'additionalData' => []
            ];

            $encounters = Encounter::where('subject', 'Patient/' . $patientSatusehatId)
                ->orderByDesc('period_start')
                ->get();

            if (!$encounters->isEmpty()) {
                foreach ($encounters as $encounter) {
                    $encSatusehatId = $encounter->resource->satusehat_id;

                    $data['encounters'][] = [
                        'encounter' => $encounter,
                        'conditions' => Condition::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                        'observations' => Observation::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                        'procedures' => Procedure::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                        'medicationRequests' => MedicationRequest::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                        'compositions' => Composition::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                        'allergyIntolerances' => AllergyIntolerance::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                        'clinicalImpression' => ClinicalImpression::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                        'serviceRequests' => ServiceRequest::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                        'medicationDispenses' => MedicationDispense::where('context', 'Encounter/' . $encSatusehatId)->get(),
                        'medicationStatements' => MedicationStatement::where('context', 'Encounter/' . $encSatusehatId)->get(),
                        'questionnaireResponses' => QuestionnaireResponse::where('encounter', 'Encounter/' . $encSatusehatId)->get(),
                    ];
                }
            }

            $data['additionalData'] = [
                'medicationRequests' => MedicationRequest::where('subject', 'Patient/' . $patientSatusehatId)->get(),
                'compositions' => Composition::where('subject', 'Patient/' . $patientSatusehatId)->get(),
                'allergyIntolerances' => AllergyIntolerance::where('patient', 'Patient/' . $patientSatusehatId)->get(),
                'medicationDispenses' => MedicationDispense::where('subject', 'Patient/' . $patientSatusehatId)->get(),
                'medicationStatements' => MedicationStatement::where('subject', 'Patient/' . $patientSatusehatId)->get(),
                'questionnaireResponses' => QuestionnaireResponse::where('subject', 'Patient/' . $patientSatusehatId)->get(),
            ];

            // Return the result as JSON
            return response()->json(['data' => $data]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
        }
    }
}
