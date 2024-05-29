<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FhirResource;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $patients = FhirResource::where('resourceType', 'Patient');

        if ($request->query('name')) {
            $patients = $patients->where('name.text', 'like', '%' . addcslashes($request->query('name'), '%_') . '%');
        }

        foreach (array_keys(config('app.identifier_systems.patient')) as $idSystem) {
            if ($request->query($idSystem)) {
                $patients = $patients
                    ->where('identifier.system', config('app.identifier_systems.patient.' . $idSystem))
                    ->where('identifier.value', 'like', '%' . addcslashes($request->query($idSystem), '%_') . '%');
            }
        }

        $patients = $patients->paginate(15)->withQueryString();

        $formattedPatients = $patients->map(function ($patient) {
            $latestEncounter = FhirResource::where('resourceType', 'Encounter')
                ->where('subject.reference', 'Patient/' . $patient['id'])
                ->orderByDesc('period.start')
                ->first();

            $identifiers = [];
            foreach ($patient['identifier'] as $identifier) {
                if ($identifier['system'] == config('app.identifier_systems.patient.nik')) {
                    $identifiers['nik'] = $identifier['value'];
                } elseif ($identifier['system'] == config('app.identifier_systems.patient.nik-ibu')) {
                    $identifiers['nik-ibu'] = $identifier['value'];
                } elseif ($identifier['system'] == config('app.identifier_systems.patient.paspor')) {
                    $identifiers['paspor'] = $identifier['value'];
                } elseif ($identifier['system'] == config('app.identifier_systems.patient.kk')) {
                    $identifiers['kk'] = $identifier['value'];
                } elseif ($identifier['system'] == config('app.identifier_systems.patient.rekam-medis')) {
                    $identifiers['rekam-medis'] = $identifier['value'];
                } elseif ($identifier['system'] == config('app.identifier_systems.patient.ihs-number')) {
                    $identifiers['ihs-number'] = $identifier['value'];
                }
            }

            return [
                'satusehatId' => data_get($patient, 'id'),
                'nik' => data_get($identifiers, 'nik'),
                'nik-ibu' => data_get($identifiers, 'nik-ibu'),
                'paspor' => data_get($identifiers, 'paspor'),
                'kk' => data_get($identifiers, 'kk'),
                'rekam-medis' => data_get($identifiers, 'rekam-medis'),
                'ihs-number' => data_get($identifiers, 'ihs-number'),
                'name' => data_get($patient, 'name.0.text'),
                'class' => data_get($latestEncounter, 'class.code'),
                'start' => data_get($latestEncounter, 'period.start'),
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

    public function getEncounterRelatedData($table, $encSatusehatId, $relation)
    {
        return FhirResource::where('resourceType', $table)
            ->where($relation . '.reference', 'Encounter/' . $encSatusehatId)
            ->get();
    }

    public function getPatientRelatedData($table, $patientUuid, $relation, $excludeRelation)
    {
        return FhirResource::where('resourceType', $table)
            ->where($relation . '.reference', 'Patient/' . $patientUuid)
            ->where($excludeRelation, '=', null)
            ->get();
    }

    public function getConditionData($encounterId)
    {
        $conditions = FhirResource::where([
            ['resourceType', 'Condition'],
            ['encounter.reference', 'Encounter/' . $encounterId]
        ])->get();
        
        $conditions->groupBy(function ($condition) {
            $category = data_get($condition, 'category.0.coding.0.code');
            $code = data_get($condition, 'code.coding.0.system');
            if ($category == 'encounter-diagnosis' && $code == 'http://hl7.org/fhir/sid/icd-10') {
                return 'diagnosis';
            } elseif ($category == 'encounter-diagnosis' && $code == 'http://snomed.info/sct') {
                return 'asesmen-harian';
            } else {
                return 'lainnya';
            }
        })->map(function ($conditions) {
            return $conditions->map(function ($condition) {
                return $condition;
            });
        });
       $data = FhirResource::where([
        ['encounter.reference', '=', 'Encounter/' . $encounterId],
        ['resourceType', '=', 'Condition']
    ])->get();
    
    // return response()->json($groupedConditions);
        return response()->json([
        'diagnosis' => $conditions->map(function ($condition) {
            return [
                'id' => $condition->id,
                'code' => $condition->code
            ];
        })
    ]);
    }

    public function show($patientId)
    {
        $patient = FhirResource::where([
            ['resourceType', 'Patient'],
            ['id', $patientId]
        ])->first();

        $data = [
            'patient' => $patient,
            'encounters' => [],
            'additionalData' => []
        ];

        $encounters = FhirResource::where('resourceType', 'Encounter')->where('subject.reference', 'Patient/' . $patientId)->get();

        $data['encounters'] = $encounters->map(function ($encounter) {
            $encSatusehatId = $encounter['id'];

            return [
                'encounter' => $encounter,
                'conditions' => $this->getConditionData($encSatusehatId),
                'observations' => $this->getEncounterRelatedData('Observation', $encSatusehatId, 'encounter'),
                'procedures' => $this->getEncounterRelatedData('Procedure', $encSatusehatId, 'encounter'),
                'medicationRequests' => $this->getEncounterRelatedData('MedicationRequest', $encSatusehatId, 'encounter'),
                'compositions' => $this->getEncounterRelatedData('Composition', $encSatusehatId, 'encounter'),
                'allergyIntolerances' => $this->getEncounterRelatedData('AllergyIntolerance', $encSatusehatId, 'encounter'),
                'clinicalImpression' => $this->getEncounterRelatedData('ClinicalImpression', $encSatusehatId, 'encounter'),
                'serviceRequests' => $this->getEncounterRelatedData('ServiceRequest', $encSatusehatId, 'encounter'),
                'medicationStatements' => $this->getEncounterRelatedData('MedicationStatement', $encSatusehatId, 'context'),
                'questionnaireResponses' => $this->getEncounterRelatedData('QuestionnaireResponse', $encSatusehatId, 'encounter'),
            ];
        });

        $data['additionalData'] = [
            'medicationRequests' => $this->getPatientRelatedData('MedicationRequest', $patientId, 'subject', 'encounter'),
            'compositions' => $this->getPatientRelatedData('Composition', $patientId, 'subject', 'encounter'),
            'allergyIntolerances' => $this->getPatientRelatedData('AllergyIntolerance', $patientId, 'patient', 'encounter'),
            'medicationStatements' => $this->getPatientRelatedData('MedicationStatement', $patientId, 'subject', 'context'),
            'questionnaireResponses' => $this->getPatientRelatedData('QuestionnaireResponse', $patientId, 'subject', 'encounter'),
        ];

        return $data;
    }

    public function getKunjunganData($resType, $encounterId)
    {
        $attr = 'Encounter';
        switch ($resType) {
            case 'Condition':
                $attr = 'encounter';
                break;
            case 'Observation':
                $attr = 'encounter';
                break;
            case 'Procedure':
                $attr = 'encounter';
                break;
            case 'MedicationRequest':
                $attr = 'encounter';
                break;
            case 'Composition':
                $attr = 'encounter';
                break;
            case 'AllergyIntolerance':
                $attr = 'encounter';
                break;
            case 'ClinicalImpression':
                $attr = 'encounter';
                break;
            case 'ServiceRequest':
                $attr = 'encounter';
                break;
            case 'MedicationStatement':
                $attr = 'context';
                break;
            case 'QuestionnaireResponse':
                $attr = 'encounter';
                break;
        }

        if ($resType == 'Condition') {
            return $this->getConditionData($encounterId);
        } else {
        $attr = 'Encounter';
        switch ($resType) {
            case 'Observation':
            case 'Procedure':
            case 'MedicationRequest':
            case 'Composition':
            case 'AllergyIntolerance':
            case 'ClinicalImpression':
            case 'ServiceRequest':
            case 'QuestionnaireResponse':
                $attr = 'encounter';
                break;
            case 'MedicationStatement':
                $attr = 'context';
                break;
        }

        return FhirResource::where('resourceType', $resType)
            ->where($attr . '.reference', 'Encounter/' . $encounterId)
            ->get();
    }
    }
}
