<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Dotenv\Dotenv;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class SatusehatController extends Controller
{
    public function getAccessToken()
    {
        $satusehatClient = new SatusehatClient();
        $response = $satusehatClient->getToken();
        return $response;
    }

    public function getResource($resourceType, $satusehatId)
    {
        $satusehatClient = new SatusehatClient();
        $response = $satusehatClient->get($resourceType, $satusehatId);
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

    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(getcwd());
        $dotenv->safeLoad();

        $this->authUrl = env('auth_url');
        $this->baseUrl = env('base_url');
        $this->consentUrl = env('consent_url');
        $this->clientId = env('client_id');
        $this->clientSecret = env('client_secret');
        $this->organizationId = env('organization_id');
    }

    public function getToken()
    {
        if (session()->has('token')) {
            if (session()->has('token_created_at')) {
                if (now()->diffInMinutes(session('token_created_at')) < 55) {
                    return session()->get('token');
                } else {
                    session()->forget('token');
                    session()->forget('token_created_at');
                }
            }
        }

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

        // $response = $client->request('POST', $url, [
        //     'headers' => $headers,
        //     'form_params' => [
        //         'client_id' => env('client_id'),
        //         'client_secret' => env('client_secret')
        //     ],
        // ]);

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
        } catch (Exception $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ));
        }
    }
}
