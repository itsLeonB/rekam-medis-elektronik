<?php

namespace App\Http\Controllers;

use App\Fhir\Codesystems;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerminologyController extends Controller
{
    public function getIcd10(Request $request)
    {
        $icd10 = DB::table(Codesystems::ICD10['table'])
            ->where('display_en', 'like', '%' . $request->query('search') . '%')
            ->orWhere('display_id', 'like', '%' . $request->query('search') . '%')
            ->get();

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
            ->get();

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
            ->orWhere('code', 'like' . '%' . $request->query('search') . '%')
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
