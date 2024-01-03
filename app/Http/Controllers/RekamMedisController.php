<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RekamMedisController extends Controller
{
    public function index()
    {
        $patients = Patient::with(['resource', 'identifier', 'name'])->get();

        $formattedPatients = $patients->map(function ($patient) {
            $latestEncounter = $this->getLatestEncounter($patient);
            $latestEncounter->start = new DateTime($latestEncounter->start);

            return [
                'satusehatId' => $patient->resource->satusehat_id,
                'identifier' => $patient->identifier()->where('system', 'rme')->value('value'),
                'name' => $patient->name()->first()->text,
                'class' => $latestEncounter->code,
                'start' => $latestEncounter->start->setTimezone(new DateTimeZone('Asia/Jakarta'))->format('Y-m-d\TH:i:sP'),
            ];
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

    public function updateData($patientId)
    {
        //todo
    }
}
