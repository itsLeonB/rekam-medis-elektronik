<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KitasehatController extends Controller
{
    public function retrieveAntrean(Request $request)
    {
        // read request data
        $data = $request->all();
    }

    public function mapToFhir($data)
    {
        $fasilitas = $data['fasilitas_id'];
        $jenisKelamin = $data['pasien']['jenis_kelamin'] ?? null;
        $statusPerkawinan = $data['pasien']['status_perkawinan'] ?? null;

        $class = 'AMB';
        $serviceType = 124;
        $gender = 'unknown';
        $maritalStatus = 'UNK';

        switch ($statusPerkawinan) {
            case 'BELUM KAWIN':
                $marital = 'S';
                break;
            case 'KAWIN':
                $marital = 'M';
                break;
            case 'CERAI HIDUP':
                $marital = 'D';
                break;
            case 'CERAI MATI':
                $marital = 'W';
                break;
            default:
                $marital = 'UNK';
                break;
        }

        switch ($jenisKelamin) {
            case 'LAKI - LAKI':
                $gender = 'male';
                break;
            case 'PEREMPUAN':
                $gender = 'female';
                break;
            default:
                $gender = 'unknown';
                break;
        }

        switch ($fasilitas) {
            case 1:
                $class = 'EMER';
                break;
            case 2:
                $serviceType = 221;
                break;
            case 3:
                $serviceType = 88;
                break;
            case 4:
                $serviceType = 219;
                break;
            case 5:
                $serviceType = 124;
                break;
            case 6:
                $serviceType = 219;
                break;
            case 7:
                $serviceType = 165;
                break;
            case 8:
                $serviceType = 380;
                break;
            case 9:
                $serviceType = 177;
                break;
            case 10:
                $serviceType = 217;
                break;
            case 11:
                $serviceType = 60;
                break;
            case 12:
                $serviceType = 142;
                break;
            case 13:
                $serviceType = 58;
                break;
            case 14:
                $serviceType = 168;
                break;
            case 15:
                $serviceType = 13;
                break;
            case 16:
                $serviceType = 186;
                break;
            case 17:
                $serviceType = 221;
                break;
            case 18:
                $serviceType = 65;
                break;
            case 19:
                $serviceType = 168;
                break;
            case 20:
                $serviceType = 286;
                break;
            case 21:
                $serviceType = 218;
                break;
            case 22:
                $serviceType = 222;
                break;
            default:
                # code...
                break;
        }
        // map data to FHIR
        $encounter = [
            'period_start' => $data['waktu'],
            'class' => $class,
            'service_type' => $serviceType,
            'status' => 'planned',
            'reason_code_text' => $data['keluhan_awal'],
        ];

        $patient = [
            'gender' => $gender,
            'birth_date' => $data['pasien']['tangal_lahir'] ?? null,
            'marital_status' => $maritalStatus,
        ];

        if (isset($data['pasien']['nama'])) {
            $patientName = [
                [
                    'use' => 'official',
                    'text' => $data['pasien']['nama'],
                ]
            ];
        }

        $patientIdentifier = [
            [
                "system" => "https://fhir.kemkes.go.id/id/nik",
                "use" => "official",
                "value" => $data['pasien']['nik'],
            ]
        ];

        if (isset($data['pasien']['no_bpjs'])) {
            $patientIdentifier[] = [
                "system" => "https://fhir.kemkes.go.id/id/bpjs",
                "use" => "official",
                "value" => $data['pasien']['no_bpjs'],
            ];
        }

        if (isset($data['pasien']['no_hp'])) {
            $patientTelecom = [
                [
                    "system" => "phone",
                    "use" => "mobile",
                    "value" => $data['pasien']['no_hp'],
                ]
            ];
        }

        if (isset($data['pasien']['foto_profil'])) {
            $patientPhoto = [
                [
                    "url" => $data['pasien']['foto_profil'],
                ]
            ];
        }
    }
}
