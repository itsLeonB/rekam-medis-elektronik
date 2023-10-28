<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SatusehatResourceController extends Controller
{
    public function getResource(Client $client, $resourceType, $satusehatId)
    {
        try {
            $url = env('base_url') . '/' . $resourceType . '/' . $satusehatId;
            $token = session()->get('token');

            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            return response()->json(json_decode($body));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
