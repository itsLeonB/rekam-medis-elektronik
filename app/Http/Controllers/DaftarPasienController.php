<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Encounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarPasienController extends Controller
{
    public function getDaftarPasien(string $class, int $serviceType)
    {
        $encounters = Encounter::join('resource', function ($join) {
            $join->on('resource.res_type', '=', DB::raw('SUBSTRING_INDEX(encounter.subject, "/", 1)'))
                ->on('resource.satusehat_id', '=', DB::raw('SUBSTRING_INDEX(encounter.subject, "/", -1)'));
        })
            ->leftJoin('patient', 'patient.resource_id', '=', 'resource.id')
            ->leftJoin('patient_name', 'patient_name.patient_id', '=', 'patient.id')
            ->leftJoin('patient_identifier', function ($join) {
                $join->on('patient_identifier.patient_id', '=', 'patient.id')
                    ->where('patient_identifier.system', '=', 'rme');
            })
            ->where('encounter.class', $class)
            ->where('encounter.service_type', $serviceType)
            ->select(
                'encounter.id',
                'patient_name.text',
                'patient_identifier.value',
                'encounter.period_start'
            )
            ->distinct('encounter.id')
            ->orderByDesc('encounter.period_start')
            ->orderBy('patient_name.id')
            ->paginate(15);

        // Return the result as JSON
        return response()->json(['encounters' => $encounters]);
    }
}
