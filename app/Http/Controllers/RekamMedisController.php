<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resources\AllergyIntolerance;
use App\Models\Fhir\Resources\ClinicalImpression;
use App\Models\Fhir\Resources\Composition;
use App\Models\Fhir\Resources\Condition;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\MedicationRequest;
use App\Models\Fhir\Resources\MedicationStatement;
use App\Models\Fhir\Resources\Observation;
use App\Models\Fhir\Resources\Patient;
use App\Models\Fhir\Resources\Procedure;
use App\Models\Fhir\Resources\QuestionnaireResponse;
use App\Models\Fhir\Resources\ServiceRequest;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RekamMedisController extends Controller
{
    public const PATIENT_RELATED_DATA = [
        'encounter' => Encounter::class,
        'condition' => Condition::class,
        'observation' => Observation::class,
        'procedure' => Procedure::class,
        'medicationRequest' => MedicationRequest::class,
        'composition' => Composition::class,
        'allergyIntolerance' => AllergyIntolerance::class,
        'clinicalImpression' => ClinicalImpression::class,
        'serviceRequest' => ServiceRequest::class,
        'medicationStatement' => MedicationStatement::class,
        'questionnaireResponse' => QuestionnaireResponse::class,
    ];

    public function index(Request $request)
    {
        $patients = Patient::query();
        $patients = $patients->with(['resource', 'identifier', 'name']);

        if ($request->query('name')) {
            $patients = $patients->whereHas('name', function ($query) use ($request) {
                $query->where('text', 'like', '%' . $request->query('name') . '%');
            });
        }

        if ($request->query('identifier')) {
            $patients = $patients->whereHas('identifier', function ($query) use ($request) {
                $search = $request->query('identifier');
                $system = explode('|', $search)[0];
                $value = explode('|', $search)[1];
                $query->where('system', $system)->where('value', $value);
            });
        }

        $patients = $patients->get();

        $formattedPatients = $patients->map(function ($patient) {
            $latestEncounter = $this->getLatestEncounter($patient);

            if (!empty($latestEncounter)) {
                $latestEncounter->start = new DateTime($latestEncounter->start);

                return [
                    'satusehatId' => $patient->resource->satusehat_id,
                    'identifier' => $patient->identifier()->where('system', 'rme')->value('value'),
                    'name' => $patient->name()->first()->text,
                    'class' => $latestEncounter->code,
                    'start' => $latestEncounter->start->setTimezone(new DateTimeZone('Asia/Jakarta'))->format('Y-m-d\TH:i:sP'),
                ];
            }
        });

        return response()->json($formattedPatients);
    }

    private function getLatestEncounter($patient)
    {
        return Encounter::join('periods', function ($join) {
            $join->on('encounter.id', '=', 'periods.periodable_id')
                ->where('periods.periodable_type', 'Encounter');
        })
            ->join('codings', function ($join) {
                $join->on('encounter.id', '=', 'codings.codeable_id')
                    ->where('codings.codeable_type', 'Encounter');
            })
            ->whereHas('subject', function ($query) use ($patient) {
                $query->where('reference', 'Patient/' . $patient->resource->satusehat_id);
            })
            ->select('codings.code', 'periods.start')
            ->orderByDesc('periods.start')
            ->first();
    }

    public function getEncounterRelatedData($model, $encSatusehatId, $relation)
    {
        return $model::whereHas($relation, function ($query) use ($encSatusehatId) {
            $query->where('reference', 'Encounter/' . $encSatusehatId);
        })->get();
    }

    public function getPatientRelatedData($model, $patientUuid, $relation, $excludeRelation)
    {
        return $model::whereHas($relation, function ($query) use ($patientUuid) {
            $query->where('reference', 'Patient/' . $patientUuid);
        })->whereDoesntHave($excludeRelation)->get();
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

            $encounters = Encounter::whereHas('subject', function ($query) use ($patientSatusehatId) {
                $query->where('reference', 'Patient/' . $patientSatusehatId);
            })->get();

            if (!$encounters->isEmpty()) {
                foreach ($encounters as $encounter) {
                    $encSatusehatId = $encounter->resource->satusehat_id;

                    $data['encounters'][] = [
                        'encounter' => $encounter,
                        'conditions' => $this->getEncounterRelatedData(Condition::class, $encSatusehatId, 'encounter'),
                        'observations' => $this->getEncounterRelatedData(Observation::class, $encSatusehatId, 'encounter'),
                        'procedures' => $this->getEncounterRelatedData(Procedure::class, $encSatusehatId, 'encounter'),
                        'medicationRequests' => $this->getEncounterRelatedData(MedicationRequest::class, $encSatusehatId, 'encounter'),
                        'compositions' => $this->getEncounterRelatedData(Composition::class, $encSatusehatId, 'encounter'),
                        'allergyIntolerances' => $this->getEncounterRelatedData(AllergyIntolerance::class, $encSatusehatId, 'encounter'),
                        'clinicalImpression' => $this->getEncounterRelatedData(ClinicalImpression::class, $encSatusehatId, 'encounter'),
                        'serviceRequests' => $this->getEncounterRelatedData(ServiceRequest::class, $encSatusehatId, 'encounter'),
                        'medicationStatements' => $this->getEncounterRelatedData(MedicationStatement::class, $encSatusehatId, 'context'),
                        'questionnaireResponses' => $this->getEncounterRelatedData(QuestionnaireResponse::class, $encSatusehatId, 'encounter'),
                    ];
                }
            }

            $data['additionalData'] = [
                'medicationRequests' => $this->getPatientRelatedData(MedicationRequest::class, $patientSatusehatId, 'subject', 'encounter'),
                'compositions' => $this->getPatientRelatedData(Composition::class, $patientSatusehatId, 'subject', 'encounter'),
                'allergyIntolerances' => $this->getPatientRelatedData(AllergyIntolerance::class, $patientSatusehatId, 'patient', 'encounter'),
                'medicationStatements' => $this->getPatientRelatedData(MedicationStatement::class, $patientSatusehatId, 'subject', 'context'),
                'questionnaireResponses' => $this->getPatientRelatedData(QuestionnaireResponse::class, $patientSatusehatId, 'subject', 'encounter'),
            ];

            // Return the result as JSON
            return response()->json(['data' => $data]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
        }
    }

    public function getData($patientId)
    {
        $responses = Http::pool(function (Pool $pool) use ($patientId) {
            foreach (self::PATIENT_RELATED_DATA as $resType => $model) {
                $resType = strtolower($resType);

                if ($resType == 'allergyintolerance') {
                    $pool->get(route('satusehat.search.' . $resType, ['patient' => $patientId]));
                } else {
                    $pool->get(route('satusehat.search.' . $resType, ['subject' => $patientId]));
                }
            }
        });

        dd($responses);
    }

    public function updateData($patientId)
    {
        $checkRequest = Request::create(route('satusehat.resource.show', ['res_type' => 'Patient', 'res_id' => $patientId]), 'GET');
        $checkResponse = app()->handle($checkRequest);

        $int = new IntegrationController(new SatusehatController());

        if ($checkResponse->isSuccessful()) {
            foreach (self::PATIENT_RELATED_DATA as $resType => $model) {
                $resType = strtolower($resType);

                if ($resType == 'allergyintolerance') {
                    $request = Request::create(route('satusehat.search.' . $resType, ['patient' => $patientId]), 'GET');
                } else {
                    $request = Request::create(route('satusehat.search.' . $resType, ['subject' => $patientId]), 'GET');
                }

                $response = app()->handle($request);

                if ($response->isSuccessful()) {
                    $bundle = json_decode($response->getContent(), true);
                    $this->bundleHandler($bundle, $resType, $int);
                }
            }

            return response()->json(['message' => 'Data pasien berhasil diperbarui'], 200);
        }

        return response()->json(['message' => 'Data pasien tidak ditemukan'], 404);
    }

    public function bundleHandler($bundle, $resType, IntegrationController $int)
    {
        if (!empty($bundle)) {
            if (!empty($bundle['entry'])) {
                foreach ($bundle['entry'] as $e) {
                    if (!empty($e['resource'])) {
                        if (isset($e['resource']['resourceType'])) {
                            if (strtolower($e['resource']['resourceType']) == strtolower($resType)) {
                                $int->updateOrCreate($resType, $e['resource']['id'], $e['resource']);
                            }
                        }
                    }
                }
            }
        }
    }
}
