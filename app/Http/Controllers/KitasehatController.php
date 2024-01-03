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
        $kontakDarurat = $data['pasien']['kontak_darurat'] ?? null;

        $class = 'AMB';
        $serviceType = 124;
        $gender = 'unknown';
        $maritalStatus = 'UNK';
        $pekerjaan = null;
        $pendidikan = null;

        $patientName = [];
        $patientTelecom = [];
        $patientPhoto = [];
        $patientContact = [];
        $observationPekerjaan = [];
        $observationPendidikan = [];

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
            'religion' => $data['pasien']['agama'] ?? null,
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

        if (isset($data['pasien']['pekerjaan'])) {
            switch ($data['pasien']['pekerjaan']) {
                case 'BELUM/ TIDAK BEKERJA':
                    $pekerjaan = null; //TODO
                    break;
                case 'PELAJAR/ MAHASISWA':
                    $pekerjaan = null; //TODO
                    break;
                case 'PENSIUNAN':
                    $pekerjaan = null; //TODO
                    break;
                case 'PEGAWAI NEGERI SIPIL ( PNS )':
                    $pekerjaan = null; //TODO
                    break;
                case 'TENTARA NASIONAL INDONESIA':
                    $pekerjaan = '0';
                    break;
                case 'PETANI/PEKEBUN':
                    $pekerjaan = '611';
                    break;
                case 'NELAYAN/PERIKANAN':
                    $pekerjaan = '622';
                    break;
                case 'PENDETA':
                    $pekerjaan = '2636';
                    break;
                case 'WAKIL GUBERNUR':
                    $pekerjaan = '111103';
                    break;
                case 'BUPATI':
                    $pekerjaan = '111104';
                    break;
                case 'GURU':
                    $pekerjaan = '23';
                    break;
                case 'PERAWAT':
                    $pekerjaan = '322';
                    break;
                case 'WIRASWASTA':
                    $pekerjaan = null; //TODO
                    break;
                case 'LAINNYA':
                    $pekerjaan = null; //TODO
                    break;
                default:
                    $pekerjaan = null;
                    break;
            }
        }

        if ($pekerjaan) {
            $observationPekerjaan = [
                'status' => 'final',
                'category' => 'social-history',
                'code' => '67875-5',
                'valueCodeableConcept' => [
                    'system' => 'kbji2014', //TODO
                    'code' => $pekerjaan,
                    'display' => $data['pasien']['pekerjaan'] //TODO
                ]
            ];
        }

        if (isset($data['pasien']['pendidikan'])) {
            switch ($data['pasien']['pendidikan']) {
                case 'Tidak/Belum Sekolah':
                    $pendidikan = '0';
                    break;
                case 'BELUM TAMAT SD/SEDERAJAT':
                    $pendidikan = '0';
                    break;
                case 'TAMAT SD/SEDERAJAT':
                    $pendidikan = '1';
                    break;
                case 'SLTP/SEDERAJAT':
                    $pendidikan = '2';
                    break;
                case 'SLTA/SEDERAJAT':
                    $pendidikan = '3';
                    break;
                case 'DIPLOMA I/II':
                    $pendidikan = '5';
                    break;
                case 'DIPLOMA IV/STRATA I':
                    $pendidikan = '6';
                    break;
                case 'AKADEMI/DIPLOMA III/S. MUDA':
                    $pendidikan = '5';
                    break;
                case 'STRATA II':
                    $pendidikan = '7';
                    break;
                case 'STRATA III':
                    $pendidikan = '8';
                    break;
                default:
                    $pendidikan = null;
                    break;
            }
        }

        if ($pendidikan) {
            $observationPendidikan = [
                'status' => 'final',
                'category' => 'social-history',
                'code' => '67875-5',
                'valueCodeableConcept' => [
                    'system' => 'ISCED2011', //TODO
                    'code' => $pendidikan,
                    'display' => $data['pasien']['pendidikan'] //TODO
                ]
            ];
        }

        if (!empty($kontakDarurat)) {
            foreach ($kontakDarurat as $kd) {
                $patientContact[] = [
                    'name' => $kd['nama'],
                    'telecom' => $kd['no_hp']
                ];
            }
        }

        return [
            'patient' => $patient,
            'encounter' => $encounter,
            'patientName' => $patientName,
            'patientIdentifier' => $patientIdentifier,
            'patientTelecom' => $patientTelecom,
            'patientPhoto' => $patientPhoto,
            'patientContact' => $patientContact,
            'observationPekerjaan' => $observationPekerjaan,
            'observationPendidikan' => $observationPendidikan
        ];
    }
}
