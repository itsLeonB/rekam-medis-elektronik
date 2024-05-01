<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $patients = DB::table('patient');

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
            $latestEncounter = DB::table('encounter')
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
        return DB::table($table)
            ->where($relation . '.reference', 'Encounter/' . $encSatusehatId)
            ->get();
    }

    public function getPatientRelatedData($table, $patientUuid, $relation, $excludeRelation)
    {
        return DB::table($table)
            ->where($relation . '.reference', 'Patient/' . $patientUuid)
            ->where($excludeRelation, '=', null)
            ->get();
    }

    public function getConditionData($encounterId)
    {
        $arr = [
            'diagnosis' => [],
            'asesmen-harian' => [],
            'lainnya' => [],
        ];

        $conditions = DB::table('condition')
            ->where('encounter.reference', 'Encounter/' . $encounterId)
            ->get();

        return $conditions->groupBy(function ($condition) {
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
    }

    public function show($patientId)
    {
        $patient = DB::table('patient')->where('id', $patientId)->first();

        $data = [
            'patient' => $patient,
            'encounters' => [],
            'additionalData' => []
        ];

        $encounters = DB::table('encounter')->where('subject.reference', 'Patient/' . $patientId)->get();

        $data['encounters'] = $encounters->map(function ($encounter) {
            $encSatusehatId = $encounter['id'];

            return [
                'encounter' => $encounter,
                'conditions' => $this->getConditionData($encSatusehatId),
                'observations' => $this->getEncounterRelatedData('observation', $encSatusehatId, 'encounter'),
                'procedures' => $this->getEncounterRelatedData('procedure', $encSatusehatId, 'encounter'),
                'medicationRequests' => $this->getEncounterRelatedData('medicationrequest', $encSatusehatId, 'encounter'),
                'compositions' => $this->getEncounterRelatedData('composition', $encSatusehatId, 'encounter'),
                'allergyIntolerances' => $this->getEncounterRelatedData('allergyintolerance', $encSatusehatId, 'encounter'),
                'clinicalImpression' => $this->getEncounterRelatedData('clinicalimpression', $encSatusehatId, 'encounter'),
                'serviceRequests' => $this->getEncounterRelatedData('servicerequest', $encSatusehatId, 'encounter'),
                'medicationStatements' => $this->getEncounterRelatedData('medicationstatement', $encSatusehatId, 'context'),
                'questionnaireResponses' => $this->getEncounterRelatedData('questionnaireresponse', $encSatusehatId, 'encounter'),
            ];
        });

        $data['additionalData'] = [
            'medicationRequests' => $this->getPatientRelatedData('medicationrequest', $patientId, 'subject', 'encounter'),
            'compositions' => $this->getPatientRelatedData('composition', $patientId, 'subject', 'encounter'),
            'allergyIntolerances' => $this->getPatientRelatedData('allergyintolerance', $patientId, 'patient', 'encounter'),
            'medicationStatements' => $this->getPatientRelatedData('medicationstatement', $patientId, 'subject', 'context'),
            'questionnaireResponses' => $this->getPatientRelatedData('questionnaireresponse', $patientId, 'subject', 'encounter'),
        ];

        return $data;
    }

    public function getKunjunganData($resType, $encounterId)
    {
        $attr = 'encounter';
        switch ($resType) {
            case 'condition':
                $attr = 'encounter';
                break;
            case 'observation':
                $attr = 'encounter';
                break;
            case 'procedure':
                $attr = 'encounter';
                break;
            case 'medicationrequest':
                $attr = 'encounter';
                break;
            case 'composition':
                $attr = 'encounter';
                break;
            case 'allergyintolerance':
                $attr = 'encounter';
                break;
            case 'clinicalimpression':
                $attr = 'encounter';
                break;
            case 'servicerequest':
                $attr = 'encounter';
                break;
            case 'medicationstatement':
                $attr = 'context';
                break;
            case 'questionnaireresponse':
                $attr = 'encounter';
                break;
        }

        if ($resType == 'condition') {
            return $this->getConditionData($encounterId);
        } else {
            return DB::table($resType)
                ->where($attr . '.reference', 'Encounter/' . $encounterId)
                ->get();
        }
    }
}
