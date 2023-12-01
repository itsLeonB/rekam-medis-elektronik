<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Inertia\Inertia;

class DaftarRekamMedisController extends Controller
{
    /**
     * Display medical record list view.
     */
    public function index()
    {
        // $daftarPasien = Resource::join('resource_content', function ($join) {
        //     $join->on('resource.res_id', '=', 'resource_content.res_id')
        //         ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        // })->join('resource_forced_id', 'resource.res_id', '=', 'resource_forced_id.res_id')
        //     ->where('resource.res_type', '=', 'Patient')
        //     ->get();

        // foreach ($daftarPasien as $dataPasien) {
        //     $resContent = json_decode($dataPasien->res_text, true);
        //     $nameData = getName($resContent);
        //     $parsedName = parseName($nameData);

        //     $identifier = getIdentifier($resContent);
        //     $nomorRM = getMRN($identifier);
        //     $rekamMedis[] = [
        //         'res_id' => $dataPasien->forced_id,
        //         'nama_pasien' => $parsedName,
        //         'nomor_rekam_medis' => $nomorRM,
        //         'res_text' => $resContent['text']['div'],
        //     ];
        // }

        // return Inertia::render('Dashboard', [
        //     'rekamMedis' => $rekamMedis
        // ]);
        return Inertia::render('Dashboard');
    }
}
