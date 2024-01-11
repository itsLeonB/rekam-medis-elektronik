<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;

class DaftarPasienController extends Controller
{
    const ENDED_STATUS = ['finished', 'cancelled', 'entered-in-error', 'unknown'];

    public function mapEncounterToPatient($encounters)
    {
        return $encounters->map(function ($encounter) {
            $patientId = explode('/', $encounter->subject->reference)[1];
            $patient = Resource::where([
                ['res_type', 'Patient'],
                ['satusehat_id', $patientId]
            ])->first()->patient()->first();

            return [
                'encounter_satusehat_id' => $encounter->resource->satusehat_id,
                'patient_name' => $patient->name()->first()->text,
                'patient_identifier' => $patient->identifier()->where('system', config('app.identifier_systems.patient.rekam-medis'))->first()->value,
                'period_start' => $encounter->period->start,
            ];
        });
    }

    public function getDaftarRawatJalan(int $serviceType)
    {
        $encounters = Encounter::whereNotIn('status', self::ENDED_STATUS)
            ->whereHas('class', function ($query) {
                $query->where('code', 'AMB');
            })
            ->whereHas('serviceType.coding', function ($query) use ($serviceType) {
                $query->where('code', $serviceType);
            })
            ->get();

        $daftarPasien = $this->mapEncounterToPatient($encounters);

        return response()->json(['daftar_pasien' => $daftarPasien], 200);
    }

    public function getDaftarRawatInap(int $serviceType)
    {
        $encounters = Encounter::whereNotIn('status', self::ENDED_STATUS)
            ->whereHas('class', function ($query) {
                $query->where('code', 'IMP');
            })
            ->whereHas('serviceType.coding', function ($query) use ($serviceType) {
                $query->where('code', $serviceType);
            })
            ->get();

        $daftarPasien = $this->mapEncounterToPatient($encounters);

        return response()->json(['daftar_pasien' => $daftarPasien], 200);
    }

    public function getDaftarIgd()
    {
        $encounters = Encounter::whereNotIn('status', self::ENDED_STATUS)
            ->whereHas('class', function ($query) {
                $query->where('code', 'EMER');
            })
            ->get();

        $daftarPasien = $this->mapEncounterToPatient($encounters);

        return response()->json(['daftar_pasien' => $daftarPasien], 200);
    }
}
