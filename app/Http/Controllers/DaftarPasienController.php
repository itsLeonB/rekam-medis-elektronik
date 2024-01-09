<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use Illuminate\Support\Facades\DB;

class DaftarPasienController extends Controller
{
    public function getDaftarPasien(string $class, int $serviceType)
    {
        $encounters = Encounter::whereHas('class', function ($query) use ($class) {
            $query->where('code', $class);
        })
            ->whereHas('serviceType.coding', function ($query) use ($serviceType) {
                $query->where('code', $serviceType);
            })
            ->get();

        $daftarPasien = $encounters->map(function ($encounter) {
            $patientId = explode('/', $encounter->subject->reference)[1];
            $patient = Resource::where([
                ['res_type', 'Patient'],
                ['satusehat_id', $patientId]
            ])->first()->patient()->first();

            return [
                'encounter_satusehat_id' => $encounter->resource->satusehat_id,
                'patient_name' => $patient->name()->first()->text,
                'patient_identifier' => $patient->identifier()->where('system', config('app.patient_identifier.rekam-medis'))->first()->value,
                'period_start' => $encounter->period->start,
            ];
        });

        return response()->json(['daftar_pasien' => $daftarPasien]);
    }
}
