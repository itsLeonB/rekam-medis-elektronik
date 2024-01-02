<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $resourceType = strtolower($resourceType);

        $resourceUpdatedAt = Resource::where([
            ['res_type', $satusehatResponseBody['resourceType']],
            ['satusehat_id', $satusehatResponseBody['id']],
        ])->first()->updated_at;
        $lastUpdated = Carbon::parse($satusehatResponseBody['meta']['lastUpdated'])->setTimezone('Asia/Jakarta');

        if ($lastUpdated->gt($resourceUpdatedAt)) {
            $request = Request::create(route($resourceType . '.update', ['satusehat_id' => $resourceId]), 'PUT', $satusehatResponseBody);
            $request->headers->set('Content-Type', 'application/json');
            $response = app('router')->dispatch($request);
            return $response;
        } else {
            return $satusehatResponseBody;
        }
    }

    public function show($resourceType, $resourceId)
    {
        $satusehatResponse = $this->satusehatController->show($resourceType, $resourceId);
        if ($satusehatResponse->getStatusCode() === 200) {
            $satusehatResponseBody = json_decode($satusehatResponse->getBody()->getContents(), true);

            if ($this->checkIfResourceExistsInLocal($resourceType, $resourceId)) {
                return $this->updateResourceIfNewer($resourceType, $resourceId, $satusehatResponseBody);
            } else {
                $resourceType = strtolower($resourceType);
                $request = Request::create(route($resourceType . '.store'), 'POST', $satusehatResponseBody);
                return app('router')->dispatch($request);
            }

            return response()->json($satusehatResponseBody, 200);
        } else {
            Log::error($satusehatResponse->getContent());

            $resourceType = strtolower($resourceType);
            $request = Request::create(route($resourceType . '.show', ['satusehat_id' => $resourceId]), 'GET');
            $localResponse = app('router')->dispatch($request);

            if ($localResponse->getStatusCode() === 200) {
                return $localResponse;
            } else {
                Log::error($localResponse->getContent());
                return $localResponse;
            }
        }
    }
}
