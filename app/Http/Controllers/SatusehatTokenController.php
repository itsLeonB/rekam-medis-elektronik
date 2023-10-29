<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SatusehatTokenController extends Controller
{
    public function getAccessToken(Client $client)
    {
        $auth_url = env('auth_url');
        $response = $client->request('POST', $auth_url . '/accesstoken?grant_type=client_credentials', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'client_id' => env('client_id'),
                'client_secret' => env('client_secret')
            ],
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody();
        $data = json_decode($body);

        if ($statusCode == 200) {
            // The access token is in the response content
            $body = $response->getBody();
            $data = json_decode($body);
            session()->put('token', $data->access_token);
            session()->put('token_created_at', now());
            // return $body;
        } else {
            // Handle the error
            return 'error';
        }
        return $data;
    }
}
