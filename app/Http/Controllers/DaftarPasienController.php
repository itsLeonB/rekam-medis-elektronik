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
        })->leftJoin('patient', function ($join) {
            $join->on('patient.resource_id', '=', 'resource.id')
                ->limit(1); // Limit to one patient
        })->leftJoin('patient_name', function ($join) {
            $join->on('patient_name.patient_id', '=', 'patient.id');
        })->leftJoin('patient_identifier', function ($join) {
            $join->on('patient_identifier.patient_id', '=', 'patient.id')
                ->where('patient_identifier.system', '=', 'rme');
        })->where('encounter.class', $class)
            ->where('encounter.service_type', $serviceType)
            ->select(
                'encounter.id', // ID kunjungan
                'patient_name.text', // Nama pasien
                'patient_identifier.value', // Nomor rekam medis
                'encounter.period_start', // Tanggal kunjungan
            )->orderBy('patient_name.period_start', 'desc') // Order by period_start in descending order
            ->orderBy('patient_name.id', 'asc') // Order by ID in ascending order to break ties when period_start is null
            ->distinct('encounter.id') // Ensure only one row per encounter
            ->get();

        // $encounters = json_encode($encounters);

        // Return the result as JSON
        return response()->json(['encounters' => $encounters]);
    }
}
