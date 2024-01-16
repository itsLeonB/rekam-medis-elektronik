<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Procedure;
use DateTime;
use DateTimeZone;

class DaftarPasienController extends Controller
{
    const ENDED_STATUS = ['finished', 'cancelled', 'entered-in-error', 'unknown'];

    private function mapEncounters($encounters)
    {
        $daftarPasien = $encounters->map(function ($encounter) {
            $patientId = explode('/', data_get($encounter, 'subject.reference'))[1];
            $patient = Resource::where([
                ['res_type', 'Patient'],
                ['satusehat_id', $patientId]
            ])->first()->patient()->first();

            $participant = data_get($encounter, 'participant.0.individual.reference');
            $practitionerId = explode('/', $participant)[1];
            $practitioner = Resource::where([
                ['res_type', 'Practitioner'],
                ['satusehat_id', $practitionerId]
            ])->first()->practitioner;

            $locationRef = data_get($encounter, 'location.0.location.reference');
            $locationId = explode('/', $locationRef)[1];
            $location = Resource::where([
                ['res_type', 'Location'],
                ['satusehat_id', $locationId]
            ])->first();

            $encounterId = data_get($encounter, 'resource.satusehat_id');

            $procedure = Procedure::whereHas('encounter', function ($query) use ($encounterId) {
                $query->where('reference', 'Encounter/' . $encounterId);
            })->first();

            return [
                'encounter_satusehat_id' => data_get($encounter, 'resource.satusehat_id'),
                'patient_satusehat_id' => $patientId,
                'patient_name' => data_get($patient, 'name.0.text'),
                'patient_identifier' => $patient->identifier()->where('system', config('app.identifier_systems.patient.rekam-medis'))->first()->value ?? null,
                'period_start' => $this->parseDate(data_get($encounter, 'period.start')),
                'encounter_status' => $encounter->status,
                'practitioner_id' => $practitionerId,
                'practitioner_name' => data_get($practitioner, 'name.0.text'),
                'procedure' => data_get($procedure, 'code.coding.0.display'),
                'location' => data_get($location, 'location.name'),
            ];
        });

        return $daftarPasien;
    }

    private function getEncounters(string $class, int $serviceType)
    {
        $encounters = Encounter::whereNotIn('status', self::ENDED_STATUS)
            ->whereHas('class', function ($query) use ($class) {
                $query->where('code', $class);
            })
            ->whereHas('serviceType.coding', function ($query) use ($serviceType) {
                $query->where('code', $serviceType);
            })
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

    public function getDaftarRawatInap(int $serviceType)
    {
        $encounters = $this->getEncounters('IMP', $serviceType);

        return $this->mapEncounters($encounters);
    }

    public function getDaftarIgd()
    {
        $encounters = Encounter::whereNotIn('status', self::ENDED_STATUS)
            ->whereHas('class', function ($query) {
                $query->where('code', 'EMER');
            })
            ->get();

        return $this->mapEncounters($encounters);
    }

    public function parseDate($date)
    {
        if (!$date) {
            return null;
        }

        $date = new DateTime($date);
        $date = $date->setTimezone(new DateTimeZone(config('app.timezone')))->format('Y-m-d\TH:i:sP');

        return $date;
    }
}
