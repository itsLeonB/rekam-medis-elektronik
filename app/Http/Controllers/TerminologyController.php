<?php

namespace App\Http\Controllers;

use App\Fhir\Codesystems;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TerminologyController extends Controller
{
    public function returnProcedureTindakan(Request $request)
    {
        $codesystem = config('app.terminologi.Procedure.code.tindakan');
        $result = $this->handleTable($request, $codesystem);

        return $result;
    }

    public function returnProcedureEdukasiBayi(Request $request)
    {
        $codesystem = config('app.terminologi.Procedure.code.edukasi-bayi');
        $result = $this->handleEcl($request, $codesystem);

        return $result;
    }

    public function returnProcedureOther(Request $request)
    {
        $codesystem = config('app.terminologi.Procedure.code.other');
        $result = $this->handleCodes($request, $codesystem);

        return $result;
    }

    public function returnConditionKunjungan(Request $request)
    {
        $codesystem = config('app.terminologi.Condition.code.kunjungan');
        $result = $this->handleTable($request, $codesystem);

        return $result;
    }

    public function returnConditionKeluar(Request $request)
    {
        $codesystem = config('app.terminologi.Condition.code.keluar');
        $result = $this->handleCodes($request, $codesystem);

        return $result;
    }

    public function returnConditionKeluhan(Request $request)
    {
        $codesystem = config('app.terminologi.Condition.code.keluhan');
        $result = $this->handleEcl($request, $codesystem);

        return $result;
    }

    public function returnConditionRiwayatPribadi(Request $request)
    {
        $codesystem = config('app.terminologi.Condition.code.riwayat-pribadi');
        $result = $this->handleTable($request, $codesystem);

        return $result;
    }

    public function returnConditionRiwayatKeluarga(Request $request)
    {
        $codesystem = config('app.terminologi.Condition.code.riwayat-keluarga');
        $result = $this->handleTable($request, $codesystem);

        return $result;
    }

    public function returnQuestionLokasiKecelakaan(Request $request)
    {
        $searchTerm = $request->query('search');

        $provinsi = $this->getWilayahCodes('kode_provinsi', 'nama_provinsi', $searchTerm);
        $kabko = $this->getWilayahCodes('kode_kabko', 'nama_kabko', $searchTerm);
        $kecamatan = $this->getWilayahCodes('kode_kecamatan', 'nama_kecamatan', $searchTerm);
        $kelurahan = $this->getWilayahCodes('kode_kelurahan', 'nama_kelurahan', $searchTerm);

        return array_merge($provinsi, $kabko, $kecamatan, $kelurahan);
    }

    private function getWilayahCodes($codeColumn, $displayColumn, $searchTerm)
    {
        $codesystem = config('app.terminologi.QuestionnaireResponseItemAnswer.valueCoding.lokasi-kecelakaan');

        $codes = DB::table($codesystem['table'])
            ->select($codeColumn, $displayColumn)
            ->where($displayColumn, 'like', '%' . $searchTerm . '%')
            ->distinct()
            ->paginate();

        return $codes->map(function ($item) use ($codesystem, $codeColumn, $displayColumn) {
            return [
                'system' => $codesystem['system'],
                'code' => $item->$codeColumn,
                'display' => $item->$displayColumn,
                'area' => $displayColumn
            ];
        })->toArray();
    }

    public function returnQuestionPoliTujuan(Request $request)
    {
        $codesystem = config('app.terminologi.QuestionnaireResponseItemAnswer.valueCoding.poli-tujuan');
        $result = $this->handleTable($request, $codesystem);

        return $result;
    }

    public function returnQuestionOther(Request $request)
    {
        $result = $this->querySnomedCt($request->query('search'), '', new Client());

        return $result;
    }

    public function returnTerminologi(Request $request)
    {
        $resType = $request->query('resourceType');
        $resAttr = $request->query('attribute');

        $valueset = config('app.terminologi.' . $resType . '.' . $resAttr);

        if (array_is_list($valueset) && count($valueset) < 4) {
            $result = [];

            foreach ($valueset as $vset) {
                if (isset($vset['url'])) {
                    $result = array_merge($result, $this->querySnomedCt($request->query('search'), '', new Client())->toArray());
                } elseif (isset($vset['ecl'])) {
                    $result = array_merge($result, $this->handleEcl($request, $vset)->toArray());
                } else {
                    $result = array_merge($result, $this->handleTable($request, $vset)->toArray());
                }
            }

            return $result;
        }

        if (isset($valueset['table'])) {
            return $this->handleTable($request, $valueset);
        }

        if (isset($valueset['ecl'])) {
            return $this->handleEcl($request, $valueset);
        }

        if (isset($valueset['system'])) {
            return $this->handleCodes($request, $valueset);
        }

        return $valueset;
    }

    private function handleCodes($request, $valueset)
    {
        if (is_array($valueset['system'])) {
            $codes = $valueset['code'];
            $codes = collect($codes)->map(function ($item) use ($valueset) {
                return [
                    'system' => $valueset['system'][$item],
                    'code' => $item,
                    'display' => $valueset['display'][$item],
                    'definition' => $valueset['definition'][$item] ?? null
                ];
            });

            return $codes;
        } else {
            $codes = $valueset['code'];
            $codes = collect($codes)->map(function ($item) use ($valueset) {
                return [
                    'system' => $valueset['system'],
                    'code' => $item,
                    'display' => $valueset['display'][$item],
                    'definition' => $valueset['definition'][$item] ?? null
                ];
            });

            return $codes;
        }
    }

    private function handleEcl($request, $valueset)
    {
        if (is_array($valueset['ecl'])) {
            $responses = [];
            foreach ($valueset['ecl'] as $ecl) {
                $item = $this->querySnomedCt($request->query('search'), $ecl, new Client());
                $responses = array_merge($responses, $item->toArray());
            }
        } else {
            $responses = $this->querySnomedCt($request->query('search'), $valueset['ecl'], new Client());
        }

        return $responses;
    }

    private function handleTable($request, $valueset)
    {
        $codes = DB::table($valueset['table']);
        if (isset($valueset['query'])) {
            $codes = $codes->where($valueset['query'][0], $valueset['query'][1], $valueset['query'][2]);
        }
        if (Schema::hasColumn($valueset['table'], 'display')) {
            $codes = $codes->where('display', 'like', '%' . $request->query('search') . '%');
        }
        if (Schema::hasColumn($valueset['table'], 'display_en')) {
            $codes = $codes->orWhere('display_en', 'like', '%' . $request->query('search') . '%');
        }
        if (Schema::hasColumn($valueset['table'], 'display_id')) {
            $codes = $codes->orWhere('display_id', 'like', '%' . $request->query('search') . '%');
        }
        if (Schema::hasColumn($valueset['table'], 'definition')) {
            $codes = $codes->orWhere('definition', 'like', '%' . $request->query('search') . '%');
        }
        if (Schema::hasColumn($valueset['table'], 'unit')) {
            $codes = $codes->orWhere('unit', 'like', '%' . $request->query('search') . '%');
        }
        $codes = $codes->get();

        if (isset($valueset['system'])) {
            $codes->map(function ($item) use ($valueset) {
                $item->system = $valueset['system'];
                return $item;
            });
        }

        return $codes;
    }

    public function getIcd10(Request $request)
    {
        $icd10 = DB::table(Codesystems::ICD10['table'])
            ->where('display_en', 'like', '%' . $request->query('search') . '%')
            ->orWhere('display_id', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        $icd10->map(function ($item) {
            $item->system = Codesystems::ICD10['system'];
            return $item;
        });

        return $icd10;
    }

    public function getIcd9CmProcedure(Request $request)
    {
        $icd9 = DB::table(Codesystems::ICD9CMProcedure['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->orWhere('definition', 'like', '%' . $request->query('search') . '%')
            ->get();

        $icd9->map(function ($item) {
            $item->system = Codesystems::ICD9CMProcedure['system'];
            return $item;
        });

        return $icd9;
    }

    public function getLoinc(Request $request)
    {
        $loinc = DB::table(Codesystems::LOINC['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->paginate(15);

        $loinc->map(function ($item) {
            $item->system = Codesystems::LOINC['system'];
            return $item;
        });

        return $loinc;
    }

    public function getSnomedCt(Request $request, Client $client)
    {
        $headers = [
            'Accept' => 'application/json',
            'Accept-Language' => 'en-X-900000000000509007,en-X-900000000000508004,en',
        ];

        $query = [
            'term' => $request->query('search'),
            'includeLeafFlag' => 'false',
            'form' => 'inferred',
            'offset' => 0,
            'limit' => 15,
        ];

        if ($request->query('ecl')) {
            $query['ecl'] = $request->query('ecl');
        }

        $response = $client->request('GET', Codesystems::SNOMEDCT['url'], [
            'headers' => $headers,
            'query' => $query,
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        $body = collect($body['items'])->map(function ($item) {
            return [
                'system' => Codesystems::SNOMEDCT['system'],
                'code' => $item['conceptId'],
                'display' => $item['fsn']['term']
            ];
        });

        return $body;
    }

    public function querySnomedCt($term, $ecl, Client $client)
    {
        $headers = [
            'Accept' => 'application/json',
            'Accept-Language' => 'en-X-900000000000509007,en-X-900000000000508004,en',
        ];

        $query = [
            'term' => $term,
            'includeLeafFlag' => 'false',
            'form' => 'inferred',
            'offset' => 0,
            'limit' => 15,
            'ecl' => $ecl
        ];

        $response = $client->request('GET', Codesystems::SNOMEDCT['url'], [
            'headers' => $headers,
            'query' => $query,
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        $body = collect($body['items'])->map(function ($item) {
            return [
                'system' => Codesystems::SNOMEDCT['system'],
                'code' => $item['conceptId'],
                'display' => $item['fsn']['term']
            ];
        });

        return $body;
    }

    public function getProvinsi(Request $request)
    {
        $provinsi = DB::table(Codesystems::AdministrativeArea['table'])
            ->select('kode_provinsi', 'nama_provinsi')
            ->where('nama_provinsi', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        return $provinsi;
    }

    public function getKabupatenKota(Request $request)
    {
        $kabupaten = DB::table(Codesystems::AdministrativeArea['table'])
            ->select('kode_kabko', 'nama_kabko')
            ->where('kode_provinsi', $request->query('kode_provinsi'))
            ->where('nama_kabko', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        return $kabupaten;
    }

    public function getKotaLahir(Request $request)
    {
        $kabupaten = DB::table(Codesystems::AdministrativeArea['table'])
            ->select('kode_kabko', 'nama_kabko', 'kode_provinsi', 'nama_provinsi')
            ->where('nama_kabko', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        return $kabupaten;
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = DB::table(Codesystems::AdministrativeArea['table'])
            ->select('kode_kecamatan', 'nama_kecamatan')
            ->where('kode_kabko', $request->query('kode_kabko'))
            ->where('nama_kecamatan', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        return $kecamatan;
    }

    public function getKelurahan(Request $request)
    {
        $kelurahan = DB::table(Codesystems::AdministrativeArea['table'])
            ->select('kode_kelurahan', 'nama_kelurahan')
            ->where('kode_kecamatan', $request->query('kode_kecamatan'))
            ->where('nama_kelurahan', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        return $kelurahan;
    }

    public function getBcp13(Request $request)
    {
        $bcp13 = DB::table(Codesystems::MimeTypes['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->orWhere('code', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        $bcp13->map(function ($item) {
            $item->system = Codesystems::MimeTypes['system'];
            return $item;
        });

        return $bcp13;
    }

    public function getBcp47(Request $request)
    {
        $bcp47 = DB::table(Codesystems::BCP47['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->orWhere('definition', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        $bcp47->map(function ($item) {
            $item->system = Codesystems::BCP47['system'];
            return $item;
        });

        return $bcp47;
    }

    public function getIso3166(Request $request)
    {
        $iso3166 = DB::table(Codesystems::ISO3166['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        $iso3166->map(function ($item) {
            $item->system = Codesystems::ISO3166['system'];
            return $item;
        });

        return $iso3166;
    }

    public function getUcum(Request $request)
    {
        $ucum = DB::table(Codesystems::UCUM['table'])
            ->where('unit', 'like', '%' . $request->query('search') . '%')
            ->orWhere('code', 'like', '%' . $request->query('search') . '%')
            ->distinct()
            ->get();

        $ucum->map(function ($item) {
            $item->system = Codesystems::UCUM['system'];
            unset($item->id);
            return $item;
        });

        return $ucum;
    }
}
