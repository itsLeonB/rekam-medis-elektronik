<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Dotenv\Dotenv;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class SatusehatController extends Controller
{
    private $satusehatClient;

    private function loadEnv() {
        $dotenv = Dotenv::createUnsafeImmutable(getcwd());
        $dotenv->safeLoad();

        return [
            'auth_url' => env('auth_url'),
            'base_url' => env('base_url'),
            'consent_url' => env('consent_url'),
            'client_id' => env('client_id'),
            'client_secret' => env('client_secret'),
            'organization_id' => env('organization_id'),
        ];
    }

    public function __construct()
    {
        $envVars = $this->loadEnv();
        $this->satusehatClient = new SatusehatClient(
            $envVars['auth_url'],
            $envVars['base_url'],
            $envVars['consent_url'],
            $envVars['client_id'],
            $envVars['client_secret'],
            $envVars['organization_id']
        );
    }

    public function getResource($resourceType, $satusehatId)
    {
        $response = $this->satusehatClient->get($resourceType, $satusehatId);
        return $response;
    }
}

class SatusehatClient
{
    public string $authUrl;
    public string $baseUrl;
    public string $consentUrl;
    public string $clientId;
    public string $clientSecret;
    public string $organizationId;

    public function __construct($authUrl, $baseUrl, $consentUrl, $clientId, $clientSecret, $organizationId)
    {
        $this->authUrl = $authUrl;
        $this->baseUrl = $baseUrl;
        $this->consentUrl = $consentUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->organizationId = $organizationId;
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
}
