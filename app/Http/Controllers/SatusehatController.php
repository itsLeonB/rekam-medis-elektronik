<?php

namespace App\Http\Controllers;

use App\Fhir\Satusehat;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fhir\ConsentRequest;
use App\Http\Requests\FhirRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SatusehatController extends Controller
{
    public string $authUrl;
    public string $baseUrl;
    public string $consentUrl;
    public string $kfaV1Url;
    public string $kfaV2Url;
    public string $clientId;
    public string $clientSecret;
    public string $organizationId;

    public function __construct()
    {
        $this->authUrl = config('app.auth_url');
        $this->baseUrl = config('app.base_url');
        $this->consentUrl = config('app.consent_url');
        $this->kfaV1Url = config('app.kfa_v1_url');
        $this->kfaV2Url = config('app.kfa_v2_url');
        $this->clientId = config('app.client_id');
        $this->clientSecret = config('app.client_secret');
        $this->organizationId = config('app.organization_id');
    }

    public function readConsent($patientId)
    {
        $token = $this->getToken();

        $client = new Client();

        $url = $this->consentUrl . '/Consent';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => ['patient_id' => $patientId],
            'verify' => false,
        ]);

        return $response;
    }

    public function updateConsent(ConsentRequest $request)
    {
        $token = $this->getToken();

        $client = new Client();

        $url = $this->consentUrl . '/Consent';

        $data = $request->validated();

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchKfaProduct(
        int $page = 1,
        int $size = 10,
        string $productType = 'farmasi', // farmasi | alkes
        string $fromDate = null,
        string $toDate = null,
        string $farmalkesType = null,
        string $keyword = null,
        int $templateCode = null,
        string $packagingCode = null,
    ) {
        $client = new Client();

        $response = $client->request('GET', config('app.kfa_v2_url') . '/products/all', [
            'query' => [
                'page' => $page,
                'size' => $size,
                'product_type' => $productType,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'farmalkes_type' => $farmalkesType,
                'keyword' => $keyword,
                'template_code' => $templateCode,
                'packaging_code' => $packagingCode,
            ],
            'verify' => false,
        ]);

        return $response->getBody()->getContents();
    }

    public function getToken()
    {
        if (session()->has('token')) {
            if (session()->has('token_created_at')) {
                if (now()->diffInMinutes(session('token_created_at')) < 55) {
                    return session()->get('token');
                }
            }
        }
        session()->forget('token');
        session()->forget('token_created_at');

        $client = new Client();
        $url = $this->authUrl . '/accesstoken?grant_type=client_credentials';
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded',];
        $options = [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
            'verify' => false,
        ];

        $request = new Request('POST', $url, $headers);

        $response = $client->sendAsync($request, $options)->wait();
        $contents = json_decode($response->getBody()->getContents());
        $token = $contents->access_token;

        session()->put('token', $token);
        session()->put('token_created_at', now());

        return $token;
    }

    public function show($resourceType, $satusehatId)
    {
        $validResourceTypes = array_keys(Satusehat::AVAILABLE_METHODS);

        $resourceType = ctype_upper($resourceType[0]) ? $resourceType : Satusehat::LOWER_CASE_MAPPING[$resourceType];

        if (!in_array($resourceType, $validResourceTypes)) {
            return response()->json([
                'error' => 'Invalid resource type. Keep in mind that resource type is case sensitive.',
                'validResourceTypes' => $validResourceTypes,
            ], 400);
        }

        $method = 'get';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$resourceType])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$resourceType],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();


        $url = $this->baseUrl . '/' . $resourceType . '/' . $satusehatId;
        $headers = ['Authorization' => 'Bearer ' . $token,];

        $request = new Request('GET', $url, $headers);

        try {
            $response = $client->sendAsync($request, ['verify' => false])->wait();
            return $response;
        } catch (ClientException $e) {
            Log::error($e->getMessage());
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }

    public function store(FhirRequest $fhirRequest, $res_type)
    {
        $resourceType = ctype_upper($res_type[0]) ? $res_type : Satusehat::LOWER_CASE_MAPPING[$res_type];

        $validator = Validator::make($fhirRequest->all(), [
            'resourceType' => ['required', Rule::in(array_keys(Satusehat::AVAILABLE_METHODS))],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $method = 'post';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$fhirRequest->input('resourceType')])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$fhirRequest->input('resourceType')],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();

        $resourceType = $fhirRequest->input('resourceType');

        if ($resourceType != $res_type) {
            return response()->json([
                'error' => 'Resource type mismatch, check your request body.',
            ], 400);
        }

        $url = $this->baseUrl . '/' . $resourceType;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ];

        $request = new Request('POST', $url, $headers, json_encode($fhirRequest->all()));

        try {
            $response = $client->sendAsync($request, ['verify' => false])->wait();
            $contents = json_decode($response->getBody()->getContents());
            return response()->json($contents, 201);
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }

    public function update(FhirRequest $fhirRequest, $res_type, $res_id)
    {
        $res_type = ctype_upper($res_type[0]) ? $res_type : Satusehat::LOWER_CASE_MAPPING[$res_type];

        $validator = Validator::make($fhirRequest->all(), [
            'resourceType' => ['required', Rule::in(array_keys(Satusehat::AVAILABLE_METHODS))],
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $method = 'put';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$fhirRequest->input('resourceType')])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$fhirRequest->input('resourceType')],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();

        $resourceType = $fhirRequest->input('resourceType');
        $id = $fhirRequest->input('id');

        if (($resourceType != $res_type) || ($id != $res_id)) {
            return response()->json([
                'error' => 'Resource type or ID mismatch, check your request body.',
            ], 400);
        }

        $url = $this->baseUrl . '/' . $resourceType . '/' . $id;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ];

        $request = new Request('PUT', $url, $headers, json_encode($fhirRequest->all()));

        try {
            $response = $client->sendAsync($request, ['verify' => false])->wait();
            $contents = json_decode($response->getBody()->getContents());
            return response()->json($contents, 200);
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }
}
