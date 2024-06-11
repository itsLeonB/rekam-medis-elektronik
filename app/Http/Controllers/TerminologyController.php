<?php

namespace App\Http\Controllers;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $codesystem = config('app.terminologi.QuestionnaireResponseItemAnswer.valueCoding.lokasi-kecelakaan');

        $codes = DB::collection($codesystem['table'])->get();

        $results = [];

        foreach ($codes as $code) {
            if (strpos(strtolower($code['nama_provinsi']), strtolower($searchTerm)) !== false) {
                array_push($results, [
                    'system' => $codesystem['system'],
                    'code' => $code['kode_provinsi'],
                    'display' => $code['nama_provinsi'],
                    'area' => 'nama_provinsi'
                ]);
            }

            if (isset($code['kabko'])) {
                foreach ($code['kabko'] as $kabko) {
                    if (strpos(strtolower($kabko['nama_kabko']), strtolower($searchTerm)) !== false) {
                        array_push($results, [
                            'system' => $codesystem['system'],
                            'code' => $kabko['kode_kabko'],
                            'display' => $kabko['nama_kabko'],
                            'area' => 'nama_kabko'
                        ]);
                    }

                    if (isset($kabko['kecamatan'])) {
                        foreach ($kabko['kecamatan'] as $kecamatan) {
                            if (strpos(strtolower($kecamatan['nama_kecamatan']), strtolower($searchTerm)) !== false) {
                                array_push($results, [
                                    'system' => $codesystem['system'],
                                    'code' => $kecamatan['kode_kecamatan'],
                                    'display' => $kecamatan['nama_kecamatan'],
                                    'area' => 'nama_kecamatan'
                                ]);
                            }

                            if (isset($kecamatan['kelurahan'])) {
                                foreach ($kecamatan['kelurahan'] as $kelurahan) {
                                    if (strpos(strtolower($kelurahan['nama_kelurahan']), strtolower($searchTerm)) !== false) {
                                        array_push($results, [
                                            'system' => $codesystem['system'],
                                            'code' => $kelurahan['kode_kelurahan'],
                                            'display' => $kelurahan['nama_kelurahan'],
                                            'area' => 'nama_kelurahan'
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $results;
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
        $codes = $valueset['code'];
        $codes = collect($codes)->map(function ($item) use ($valueset) {
            return [
                'system' => is_array($valueset['system']) ? $valueset['system'][$item] : $valueset['system'],
                'code' => $item,
                'display' => $valueset['display'][$item],
                'definition' => $valueset['definition'][$item] ?? null
            ];
        });

        $search = $request->query('search');
        $codes = $codes->filter(function ($code) use ($search) {
            return stripos($code['display'], $search) !== false || stripos($code['definition'], $search) !== false;
        });

        return $codes;
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

        $codes->transform(function ($item) use ($valueset) {
            if (isset($valueset['system'])) {
                $item['system'] = $valueset['system'];
            }
            unset($item['_id']);
            return $item;
        });

        return $codes;
    }

    public function getIcd10(Request $request)
    {
        $icd10 = DB::table(Codesystems::ICD10['table'])
            ->where('display_en', 'like', '%' . $request->query('search') . '%')
            ->orWhere('display_id', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        $icd10->getCollection()->transform(function ($item) {
            $item['system'] = Codesystems::ICD10['system'];
            unset($item['_id']);
            return $item;
        });

        return $icd10;
    }

    public function getIcd9CmProcedure(Request $request)
    {
        $icd9 = DB::table(Codesystems::ICD9CMProcedure['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->orWhere('definition', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        $icd9->getCollection()->transform(function ($item) {
            $item['system'] = Codesystems::ICD9CMProcedure['system'];
            unset($item['_id']);
            return $item;
        });

        return $icd9;
    }

    public function getLoinc(Request $request)
    {
        $loinc = DB::table(Codesystems::LOINC['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        $loinc->getCollection()->transform(function ($item) {
            $item['system'] = Codesystems::LOINC['system'];
            unset($item['_id']);
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
            ->get();

        $provinsi->transform(function ($item) {
            unset($item['_id']);
            return $item;
        });

        return $provinsi;
    }

    public function getKabupatenKota(Request $request)
    {
        $kabupaten = DB::table(Codesystems::AdministrativeArea['table'])
            ->select(['kabko.kode_kabko', 'kabko.nama_kabko'])
            ->where('kode_provinsi', $request->query('kode_provinsi'))
            ->first();

        $filteredArray = Arr::where($kabupaten['kabko'], function ($value, $key) use ($request) {
            return stripos($value['nama_kabko'], $request->query('search')) !== false;
        });

        return array_values($filteredArray);
    }

    public function getKotaLahir(Request $request)
    {
        $kabupaten = DB::table(Codesystems::AdministrativeArea['table'])
            ->select(['kabko.kode_kabko', 'kabko.nama_kabko', 'kode_provinsi', 'nama_provinsi'])
            ->where('kabko.nama_kabko', 'like', '%' . $request->query('search') . '%')
            ->get();

        $filteredKabko = $kabupaten->flatMap(function ($item) use ($request) {
            return collect($item['kabko'])->filter(function ($kabko) use ($request) {
                return stripos($kabko['nama_kabko'], $request->query('search')) !== false;
            })->map(function ($kabko) use ($item) {
                return [
                    'kode_kabko' => $kabko['kode_kabko'],
                    'nama_kabko' => $kabko['nama_kabko'],
                    'kode_provinsi' => $item['kode_provinsi'],
                    'nama_provinsi' => $item['nama_provinsi'],
                ];
            });
        })->values();

        return $filteredKabko;
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = DB::table(Codesystems::AdministrativeArea['table'])
            ->select(['kabko.kode_kabko', 'kabko.kecamatan.kode_kecamatan', 'kabko.kecamatan.nama_kecamatan'])
            ->where('kabko.kode_kabko', $request->query('kode_kabko'))
            ->first();

        $filteredArray = Arr::where($kecamatan['kabko'], function ($value, $key) use ($request) {
            return $value['kode_kabko'] === $request->query('kode_kabko');
        });

        $filteredArray = Arr::where(array_values($filteredArray)[0]['kecamatan'], function ($value, $key) use ($request) {
            return stripos($value['nama_kecamatan'], $request->query('search')) !== false;
        });

        return array_values($filteredArray);
    }

    public function getKelurahan(Request $request)
    {
        $kelurahan = DB::table(Codesystems::AdministrativeArea['table'])
            ->select(['kabko.kecamatan.kode_kecamatan', 'kabko.kecamatan.kelurahan'])
            ->where('kabko.kecamatan.kode_kecamatan', $request->query('kode_kecamatan'))
            ->first();

        $filteredKelurahan = collect($kelurahan['kabko'])->flatMap(function ($kabko) use ($request) {
            return collect($kabko['kecamatan'])->filter(function ($kecamatan) use ($request) {
                return $kecamatan['kode_kecamatan'] == $request->query('kode_kecamatan');
            });
        });

        $filteredArray = Arr::where($filteredKelurahan[0]['kelurahan'], function ($value, $key) use ($request) {
            return stripos($value['nama_kelurahan'], $request->query('search')) !== false;
        });

        return array_values($filteredArray);
    }

    public function getBcp13(Request $request)
    {
        $bcp13 = DB::table(Codesystems::MimeTypes['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->orWhere('code', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        $bcp13->getCollection()->transform(function ($item) {
            $item['system'] = Codesystems::MimeTypes['system'];
            unset($item['_id']);
            return $item;
        });

        return $bcp13;
    }

    public function getBcp47(Request $request)
    {
        $bcp47 = DB::table(Codesystems::BCP47['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->orWhere('definition', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        $bcp47->getCollection()->transform(function ($item) {
            $item['system'] = Codesystems::BCP47['system'];
            unset($item['_id']);
            return $item;
        });

        return $bcp47;
    }

    public function getIso3166(Request $request)
    {
        $iso3166 = DB::table(Codesystems::ISO3166['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        $iso3166->getCollection()->transform(function ($item) {
            $item['system'] = Codesystems::ISO3166['system'];
            unset($item['_id']);
            return $item;
        });

        return $iso3166;
    }

    public function getUcum(Request $request)
    {
        $ucum = DB::table(Codesystems::UCUM['table'])
            ->where('unit', 'like', '%' . $request->query('search') . '%')
            ->orWhere('code', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        $ucum->getCollection()->transform(function ($item) {
            $item['system'] = Codesystems::UCUM['system'];
            unset($item['_id']);
            return $item;
        });

        return $ucum;
    }

    public function getKptl(Request $request)
    {
        $kptl = DB::table(Valuesets::KPTL['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->orWhere('code', 'like', '%' . $request->query('search') . '%')
            ->paginate(30);

        $kptl->getCollection()->transform(function ($item) {
            $item['system'] = Valuesets::KPTL['system'];
            unset($item['_id']);
            return $item;
        });

        return $kptl;
    }

    public function getKPTLModifier(Request $request)
    {
        // Retrieve the 'search' query parameter
        $search = $request->query('category');

        // Check if sea$search is not an array and convert if necessary
        if (!is_array($search)) {
            $search = explode(', ', $search);
        }

        // Query the database using the sea$search array
        $mod = DB::table(Valuesets::KPTLModifiers['table'])
            ->where('display', 'like', '%' . $request->query('search') . '%')
            ->orWhere('code', 'like', '%' . $request->query('search') . '%')
            ->whereIn('Kategori', $search)
            ->paginate(30);

        // Transform the collection
        $mod->getCollection()->transform(function ($item) {
            unset($item['_id']);
            $item['label'] = $item['Kategori'] . " | " . $item['display'];
            return $item;
        });

        return $mod;
    }
}
