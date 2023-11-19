<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcedureRequest;
use App\Http\Resources\ProcedureResource;
use App\Services\FhirService;

class ProcedureController extends Controller
{
    /**
     * Store a new procedure.
     *
     * @param ProcedureRequest $request The request object containing the procedure data.
     * @param FhirService $fhirService The FhirService instance for inserting data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the newly created procedure.
     */
    public function store(ProcedureRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('Procedure');
            $procedure = $resource->procedure()->create($body['procedure']);
            $this->createChildModels($procedure, $body, ['identifier', 'basedOn', 'partOf', 'performer', 'reason', 'bodySite', 'report', 'complication', 'followUp', 'note', 'focalDevice', 'itemUsed']);
            $this->createResourceContent(ProcedureResource::class, $resource);
            return response()->json($resource->procedure->first(), 201);
        });
    }


    /**
     * Update a procedure.
     *
     * @param ProcedureRequest $request The request object containing the procedure data.
     * @param int $res_id The ID of the resource.
     * @param FhirService $fhirService The FhirService instance.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated procedure.
     */
    public function update(ProcedureRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $procedure = $resource->procedure()->first();
            $procedure->update($body['procedure']);
            $procedureId = $procedure->id;
            $this->updateChildModels($procedure, $body, ['identifier', 'basedOn', 'partOf', 'performer', 'reason', 'bodySite', 'report', 'complication', 'followUp', 'note', 'focalDevice', 'itemUsed'], 'procedure_id', $procedureId);
            $this->createResourceContent(ProcedureResource::class, $resource);
            return response()->json($resource->procedure->first(), 200);
        });
    }
}
