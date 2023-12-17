<?php

namespace App\Http\Controllers;

use App\Fhir\Satusehat;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SatusehatController extends Controller
{
    public string $authUrl;
    public string $baseUrl;
    public string $consentUrl;
    public string $clientId;
    public string $clientSecret;
    public string $organizationId;

    public function __construct()
    {
        $this->authUrl = config('app.auth_url');
        $this->baseUrl = config('app.base_url');
        $this->consentUrl = config('app.consent_url');
        $this->clientId = config('app.client_id');
        $this->clientSecret = config('app.client_secret');
        $this->organizationId = config('app.organization_id');
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
        ];

        $request = new Request('POST', $url, $headers);

        $response = $client->sendAsync($request, $options)->wait();
        $contents = json_decode($response->getBody()->getContents());
        $token = $contents->access_token;

        session()->put('token', $token);
        session()->put('token_created_at', now());
        return $token;
    }

    public function get($resourceType, $satusehatId)
    {
        $validResourceTypes = array_keys(Satusehat::AVAILABLE_METHODS);

        if (!in_array($resourceType, $validResourceTypes)) {
            return response()->json([
                'error' => 'Invalid resource type. Keep in mind that resource type is case sensitive.',
                'validResourceTypes' => $validResourceTypes,
            ]);
        }

        $method = 'get';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$resourceType])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$resourceType],
            ]);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/' . $resourceType . '/' . $satusehatId;
        $headers = ['Authorization' => 'Bearer ' . $token,];

        $request = new Request('GET', $url, $headers);

        try {
            $response = $client->sendAsync($request)->wait();
            $contents = json_decode($response->getBody()->getContents());
            return $contents;
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ));
        }
    }

    public function post(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'resourceType' => ['required', Rule::in(array_keys(Satusehat::AVAILABLE_METHODS))],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $method = 'post';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$request->input('resourceType')])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$request->input('resourceType')],
            ]);
        }

        $token = $this->getToken();

        $client = new Client();

        $resourceType = $request->input('resourceType');

        $url = $this->baseUrl . '/' . $resourceType;
        $headers = ['Authorization' => 'Bearer ' . $token,];

        $request = new Request('POST', $url, $headers, $validator->validated());

        try {
            $response = $client->sendAsync($request)->wait();
            $contents = json_decode($response->getBody()->getContents());
            return $contents;
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ));
        }
    }

    public function put(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'resourceType' => ['required', Rule::in(array_keys(Satusehat::AVAILABLE_METHODS))],
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $method = 'put';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$request->input('resourceType')])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$request->input('resourceType')],
            ]);
        }

        $token = $this->getToken();

        $client = new Client();

        $resourceType = $request->input('resourceType');
        $id = $request->input('id');

        $url = $this->baseUrl . '/' . $resourceType . '/' . $id;
        $headers = ['Authorization' => 'Bearer ' . $token,];

        $request = new Request('PUT', $url, $headers, $validator->validated());

        try {
            $response = $client->sendAsync($request)->wait();
            $contents = json_decode($response->getBody()->getContents());
            return $contents;
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ));
        }
    }
}
