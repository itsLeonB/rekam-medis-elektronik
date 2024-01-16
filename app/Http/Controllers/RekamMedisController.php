<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Http\Resources\ClinicalImpressionResource;
use App\Http\Resources\CompositionResource;
use App\Http\Resources\ConditionResource;
use App\Http\Resources\EncounterResource;
use App\Http\Resources\MedicationRequestResource;
use App\Http\Resources\MedicationStatementResource;
use App\Http\Resources\ObservationResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\ProcedureResource;
use App\Http\Resources\QuestionnaireResponseResource;
use App\Http\Resources\ServiceRequestResource;
use App\Models\Fhir\Resource;
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
                $query->where('text', 'like', '%' . addcslashes($request->query('name'), '%_') . '%');
            });
        }

        foreach (array_keys(config('app.identifier_systems.patient')) as $idSystem) {
            if ($request->query($idSystem)) {
                $patients = $patients->whereHas('identifier', function ($query) use ($request, $idSystem) {
                    $query->where('system', config('app.identifier_systems.patient.' . $idSystem))
                        ->where('value', 'like', '%' . addcslashes($request->query($idSystem), '%_') . '%');
                });
            }
        }

        $patients = $patients->paginate(15)->withQueryString();

        $formattedPatients = $patients->map(function ($patient) {
            $latestEncounter = $this->getLatestEncounter($patient);

            return [
                'satusehatId' => data_get($patient, 'resource.satusehat_id'),
                'nik' => $patient->identifier()->where('system', config('app.identifier_systems.patient.nik'))->value('value') ?? null,
                'nik-ibu' => $patient->identifier()->where('system', config('app.identifier_systems.patient.nik-ibu'))->value('value') ?? null,
                'paspor' => $patient->identifier()->where('system', config('app.identifier_systems.patient.paspor'))->value('value') ?? null,
                'kk' => $patient->identifier()->where('system', config('app.identifier_systems.patient.kk'))->value('value') ?? null,
                'rekam-medis' => $patient->identifier()->where('system', config('app.identifier_systems.patient.rekam-medis'))->value('value'),
                'ihs-number' => $patient->identifier()->where('system', config('app.identifier_systems.patient.ihs-number'))->value('value') ?? null,
                'name' => data_get($patient, 'name.0.text'),
                'class' => data_get($latestEncounter, 'class.code'),
                'start' => $this->parseDate(data_get($latestEncounter, 'period.start')),
                'serviceType' => data_get($latestEncounter, 'serviceType.coding.0.code')
            ];
        });

        return [
            'rekam_medis' => [
                'current_page' => $patients->currentPage(),
                'data' => $formattedPatients,
                'first_page_url' => $patients->url(1),
                'from' => $patients->firstItem(),
                'last_page' => $patients->lastPage(),
                'last_page_url' => $patients->url($patients->lastPage()),
                'links' => $patients->links(),
                'next_page_url' => $patients->nextPageUrl() ?? null,
                'path' => $patients->path(),
                'per_page' => $patients->perPage(),
                'prev_page_url' => $patients->previousPageUrl() ?? null,
                'to' => $patients->lastItem(),
                'total' => $patients->total(),
            ],
        ];
    }

    private function getLatestEncounter($patient)
    {
        $enc = Encounter::join('periods', function ($join) {
            $join->on('encounter.id', '=', 'periods.periodable_id')
                ->where('periods.periodable_type', 'Encounter');
        })
            ->whereHas('subject', function ($query) use ($patient) {
                $query->where('reference', 'Patient/' . $patient->resource->satusehat_id);
            })
            ->orderByDesc('periods.start')
            ->first();

        if (!empty($enc)) {
            $enc = $enc->resource->satusehat_id;
            $enc = Resource::where('satusehat_id', $enc)->first()->encounter;
        } else {
            $enc = null;
        }


        return $enc;
    }

    public function getEncounterRelatedData($model, $encSatusehatId, $relation, $resource)
    {
        $data = $model::whereHas($relation, function ($query) use ($encSatusehatId) {
            $query->where('reference', 'Encounter/' . $encSatusehatId);
        })->get();

        return $data->map(function ($item) use ($resource) {
            return new $resource($item->resource);
        });
    }

    public function getPatientRelatedData($model, $patientUuid, $relation, $excludeRelation, $resource)
    {
        $data = $model::whereHas($relation, function ($query) use ($patientUuid) {
            $query->where('reference', 'Patient/' . $patientUuid);
        })->whereDoesntHave($excludeRelation)->get();

        return $data->map(function ($item) use ($resource) {
            return new $resource($item->resource);
        });
    }

    public function show($patientId)
    {
        $patient = Resource::where('satusehat_id', $patientId)->where('res_type', 'Patient')->firstOrFail();

        $data = [
            'patient' => new PatientResource($patient),
            'encounters' => [],
            'additionalData' => []
        ];

        $encounters = Encounter::whereHas('subject', function ($query) use ($patientId) {
            $query->where('reference', 'Patient/' . $patientId);
        })->get();

        $data['encounters'] = $encounters->map(function ($encounter) {
            $encSatusehatId = $encounter->resource->satusehat_id;

            return [
                'encounter' => new EncounterResource($encounter),
                'conditions' => $this->getEncounterRelatedData(Condition::class, $encSatusehatId, 'encounter', ConditionResource::class),
                'observations' => $this->getEncounterRelatedData(Observation::class, $encSatusehatId, 'encounter', ObservationResource::class),
                'procedures' => $this->getEncounterRelatedData(Procedure::class, $encSatusehatId, 'encounter', ProcedureResource::class),
                'medicationRequests' => $this->getEncounterRelatedData(MedicationRequest::class, $encSatusehatId, 'encounter', MedicationRequestResource::class),
                'compositions' => $this->getEncounterRelatedData(Composition::class, $encSatusehatId, 'encounter', CompositionResource::class),
                'allergyIntolerances' => $this->getEncounterRelatedData(AllergyIntolerance::class, $encSatusehatId, 'encounter', AllergyIntoleranceResource::class),
                'clinicalImpression' => $this->getEncounterRelatedData(ClinicalImpression::class, $encSatusehatId, 'encounter', ClinicalImpressionResource::class),
                'serviceRequests' => $this->getEncounterRelatedData(ServiceRequest::class, $encSatusehatId, 'encounter', ServiceRequestResource::class),
                'medicationStatements' => $this->getEncounterRelatedData(MedicationStatement::class, $encSatusehatId, 'context', MedicationStatementResource::class),
                'questionnaireResponses' => $this->getEncounterRelatedData(QuestionnaireResponse::class, $encSatusehatId, 'encounter', QuestionnaireResponseResource::class),
            ];
        });

        $data['additionalData'] = [
            'medicationRequests' => $this->getPatientRelatedData(MedicationRequest::class, $patientId, 'subject', 'encounter', MedicationRequestResource::class),
            'compositions' => $this->getPatientRelatedData(Composition::class, $patientId, 'subject', 'encounter', CompositionResource::class),
            'allergyIntolerances' => $this->getPatientRelatedData(AllergyIntolerance::class, $patientId, 'patient', 'encounter', AllergyIntoleranceResource::class),
            'medicationStatements' => $this->getPatientRelatedData(MedicationStatement::class, $patientId, 'subject', 'context', MedicationStatementResource::class),
            'questionnaireResponses' => $this->getPatientRelatedData(QuestionnaireResponse::class, $patientId, 'subject', 'encounter', QuestionnaireResponseResource::class),
        ];

        return $data;
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

    public function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        $date = new DateTime($date);
        $date = $date->setTimezone(new DateTimeZone(config('app.timezone')))->format('Y-m-d\TH:i:sP');

        return $date;
    }
}
