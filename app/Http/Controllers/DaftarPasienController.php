<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FhirResource;
use Illuminate\Support\Facades\DB;

class DaftarPasienController extends Controller
{
    const ENDED_STATUS = ['finished', 'cancelled'];

    private function mapEncounters($encounters)
    {
        $daftarPasien = $encounters->map(function ($encounter) {
            $patientId = explode('/', data_get($encounter, 'subject.reference'))[1];
            $patient = FhirResource::where([
                ['resourceType', 'patient'],
                ['id', $patientId]
            ])->first();

            $patientRMID = null;
            $identifiers = data_get($patient, 'identifier');

            foreach ($identifiers as $identifier) {
                if (data_get($identifier, 'system') == config('app.identifier_systems.patient.rekam-medis')) {
                    $patientRMID = data_get($identifier, 'value');
                    break;
                }
            }

            $participant = data_get($encounter, 'participant.0.individual.reference');
            $practitionerId = explode('/', $participant)[1];
            $practitioner = FhirResource::where([
                ['resourceType', 'practitioner'],
                ['id', $practitionerId]
            ])->first();

            $locationRef = data_get($encounter, 'location.0.location.reference');
            $locationId = explode('/', $locationRef)[1];
            $location = FhirResource::where([
                ['resourceType', 'location'],
                ['id', $locationId]
            ])->first();

            $encounterId = data_get($encounter, 'id');

            $procedure = FhirResource::where([
                ['resourceType', 'procedure'],
                ['encounter.reference', 'Encounter/' . $encounterId]
            ])->first();

            return [
                'encounter_satusehat_id' => $encounterId,
                'patient_satusehat_id' => $patientId,
                'patient_name' => data_get($patient, 'name.0.text'),
                'patient_identifier' => $patientRMID,
                'period_start' => data_get($encounter, 'period.start'),
                'encounter_status' => data_get($encounter, 'status'),
                'practitioner_id' => $practitionerId,
                'practitioner_name' => data_get($practitioner, 'name.0.text'),
                'procedure' => data_get($procedure, 'code.coding.0.display'),
                'location' => data_get($location, 'location.name'),
            ];
        });

        return $daftarPasien;
    }

    private function getEncounters(string $class, string $serviceType)
    {
        $encounters = FhirResource::where([
            ['resourceType', 'encounter'],
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

    public function getDaftarPoliNeurologi()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.neurologi'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarPoliObgyn()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.obgyn'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarPoliGigi()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.gigi'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarPoliKulit()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.kulit'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarPoliOrtopedi()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.ortopedi'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarPoliDalam()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.dalam'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarPoliBedah()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.bedah'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarPoliAnak()
    {
        $encounters = $this->getEncounters('AMB', config('app.kode_poli.anak'));

        return $this->mapEncounters($encounters);
    }

    public function getDaftarRawatInap()
    {
        $encounters = FhirResource::where([
            ['resourceType', 'encounter'],
            ['class.code', 'IMP']
        ])
            ->whereNotIn('status', self::ENDED_STATUS)
            ->get();

        return $this->mapEncounters($encounters);
    }

    public function getDaftarIgd()
    {
        $encounters = FhirResource::where([
            ['resourceType', 'encounter'],
            ['class.code', 'EMER']
        ])
            ->whereNotIn('status', self::ENDED_STATUS)
            ->get();

        return $this->mapEncounters($encounters);
    }
}
