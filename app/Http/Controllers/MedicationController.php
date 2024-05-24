<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FhirResource;
use Illuminate\Support\Facades\DB;

class MedicationController extends Controller
{
    const ENDED_STATUS = ['finished', 'cancelled'];

    private function mapEncounters($encounters)
    {
        $daftarObat = $encounters->map(function ($encounter) {
            $medicationId = explode('/', data_get($encounter, 'subject.reference'))[1];
            $medication = FhirResource::where([
                ['resourceType', 'Medication'],
                ['id', $medicationId]
            ])->first();

            $patientRMID = null;
            $identifiers = data_get($medication, 'identifier');

            foreach ($identifiers as $identifier) {
                if (data_get($identifier, 'system') == config('app.identifier_systems.medication')) {
                    $patientRMID = data_get($identifier, 'value');
                    break;
                }
            }

            $participant = data_get($encounter, 'participant.0.individual.reference');
            $practitionerId = explode('/', $participant)[1];
            $practitioner = FhirResource::where([
                ['resourceType', 'Practitioner'],
                ['id', $practitionerId]
            ])->first();

            $locationRef = data_get($encounter, 'location.0.location.reference');
            $locationId = explode('/', $locationRef)[1];
            $location = FhirResource::where([
                ['resourceType', 'Location'],
                ['id', $locationId]
            ])->first();

            $encounterId = data_get($encounter, 'id');

            $procedure = FhirResource::where([
                ['resourceType', 'Procedure'],
                ['encounter.reference', 'Encounter/' . $encounterId]
            ])->first();

            return [
                'encounter_satusehat_id'
                 => $encounterId,
                'medication_satusehat_id' => $medicationId,
                'medication_name' => data_get($medication, 'name.0.text'),
                'patient_identifier' => $patientRMID,
                'period_start' => data_get($encounter, 'period.start'),
                'encounter_status' => data_get($encounter, 'status'),
                'practitioner_id' => $practitionerId,
                'practitioner_name' => data_get($practitioner, 'name.0.text'),
                'procedure' => data_get($procedure, 'code.coding.0.display'),
                'location' => data_get($location, 'location.name'),
            ];
        });

        return $daftarObat;
    }

    private function getMedication(string $class, string $serviceType)
    {
        $medications = FhirResource::where([
            ['resourceType', 'Medication'],
            ['class.code', $class],
            ['serviceType.coding.0.code', $serviceType]
        ])
            ->get();

        return $medications;
    }

    private function getEncounters(string $class, string $serviceType)
    {
        $encounters = FhirResource::where([
            ['resourceType', 'Encounter'],
            ['class.code', $class],
            ['serviceType.coding.0.code', $serviceType]
        ])
            ->whereNotIn('status', self::ENDED_STATUS)
            ->get();

        return $encounters;
    }

    public function getDaftarPoliUmum()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.umum'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarRawatInap()
    {
        $encounters = FhirResource::where([
            ['resourceType', 'Encounter'],
            ['class.code', 'IMP']
        ])
            ->whereNotIn('status', self::ENDED_STATUS)
            ->get();

        return $this->mapEncounters($encounters);
    }

    public function getDaftarObat()
    {
        $medication = FhirResource::where([
            ['resourceType', 'Medication'],
            ['class.code', 'IMP']
        ])
            ->get();

        return $this->mapEncounters($medication);
    }
}
