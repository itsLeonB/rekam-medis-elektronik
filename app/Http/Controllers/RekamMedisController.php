<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    public function index()
    {
        $patients = Resource::join('patient', 'patient.resource_id', '=', 'resource.id')
            ->leftJoin('encounter', function ($join) {
                $join->on('encounter.subject', '=', DB::raw('CONCAT("Patient/", resource.satusehat_id)'))
                    ->whereColumn('encounter.subject', '=', DB::raw('CONCAT("Patient/", resource.satusehat_id)'))
                    ->orderByDesc('encounter.period_start')
                    ->limit(1);
            })
            ->leftJoin('patient_name', function ($join) {
                $join->on('patient_name.patient_id', '=', 'patient.id')
                    ->orderByDesc('patient_name.period_start')
                    ->limit(1);
            })
            ->leftJoin('patient_identifier', function ($join) {
                $join->on('patient_identifier.patient_id', '=', 'patient.id')
                    ->where('patient_identifier.system', '=', 'rme')
                    ->limit(1);
            })
            ->select(
                'patient.id',
                'patient_name.text',
                'patient_identifier.value',
                'encounter.class',
                'encounter.period_start'
            )
            ->orderByDesc('encounter.period_start')
            ->distinct('patient.id')
            ->get();


        // Return the result as JSON
        return response()->json(['patients' => $patients]);
    }
}
