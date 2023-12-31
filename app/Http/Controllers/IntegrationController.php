<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IntegrationController extends Controller
{
    public SatusehatController $satusehatController;

    public function __construct(SatusehatController $satusehatController)
    {
        $this->satusehatController = $satusehatController;
    }

    public function checkIfResourceExistsInLocal($resourceType, $resourceId)
    {
        return Resource::where([
            ['res_type', $resourceType],
            ['satusehat_id', $resourceId],
        ])->exists();
    }

    public function updateResourceIfNewer($resourceType, $resourceId, $satusehatResponseBody)
    {
        $resourceUpdatedAt = Resource::where([
            ['res_type', $satusehatResponseBody['resourceType']],
            ['satusehat_id', $satusehatResponseBody['id']],
        ])->first()->updated_at;
        $lastUpdated = $satusehatResponseBody['meta']['lastUpdated'];
        if ($lastUpdated > $resourceUpdatedAt) {
            return Http::post(route($resourceType . '.update', ['satusehat_id' => $resourceId]), $satusehatResponseBody);
        } else {
            return $satusehatResponseBody;
        }
    }

    public function get($resourceType, $resourceId)
    {
        $satusehatResponse = $this->satusehatController->get($resourceType, $resourceId);

        if ($satusehatResponse->getStatusCode() === 200) {
            $satusehatResponseBody = json_decode($satusehatResponse->getContent(), true);

            if ($this->checkIfResourceExistsInLocal($resourceType, $resourceId)) {
                return $this->updateResourceIfNewer($resourceType, $resourceId, $satusehatResponseBody);
            } else {
                return Http::post(route($resourceType . '.store'), $satusehatResponseBody);
            }

            return response()->json($satusehatResponseBody, 200);
        } else {
            Log::error($satusehatResponse->getContent());

            $resourceType = strtolower($resourceType);

            $localResponse = Http::get(route($resourceType . '.show', ['res_id' => $resourceId]));

            if ($localResponse->getStatusCode() === 200) {
                return $localResponse;
            } else {
                Log::error($localResponse->getBody()->getContents());
                return $localResponse;
            }
        }
    }
}
