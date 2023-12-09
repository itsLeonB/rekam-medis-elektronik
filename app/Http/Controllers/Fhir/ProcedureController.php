<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fhir\ProcedureRequest;
use App\Http\Resources\ProcedureResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ProcedureController extends Controller
{
    const RESOURCE_TYPE = 'Procedure';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new ProcedureResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(ProcedureRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $procedure = $resource->procedure()->create($body['procedure']);
            $this->createChildModels($procedure, $body, ['identifier', 'performer', 'note', 'focalDevice']);
            $this->createResourceContent(ProcedureResource::class, $resource);
            return response()->json($procedure, 201);
        });
    }


    public function update(ProcedureRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $procedure = $resource->procedure()->first();
            $procedure->update($body['procedure']);
            $this->updateChildModels($procedure, $body, ['identifier', 'performer', 'note', 'focalDevice']);
            $this->createResourceContent(ProcedureResource::class, $resource);
            return response()->json($procedure, 200);
        });
    }
}
