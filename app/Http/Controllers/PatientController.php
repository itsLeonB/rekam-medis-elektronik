<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function getFromApi(Client $client, $satusehatId)
    {
        $response = $client->request('GET', env('base_url') . '/Patient', [
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
    }
}
