<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcedureRequest;
use App\Http\Resources\ProcedureResource;
use App\Models\Procedure;
use App\Models\ProcedureBasedOn;
use App\Models\ProcedureBodySite;
use App\Models\ProcedureComplication;
use App\Models\ProcedureFocalDevice;
use App\Models\ProcedureFollowUp;
use App\Models\ProcedureIdentifier;
use App\Models\ProcedureItemUsed;
use App\Models\ProcedureNote;
use App\Models\ProcedurePartOf;
use App\Models\ProcedurePerformer;
use App\Models\ProcedureReason;
use App\Models\ProcedureReport;
use App\Models\Resource;
use App\Models\ResourceContent;
use App\Services\FhirService;

class ProcedureController extends Controller
{
    public function postProcedure(ProcedureRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            [$resource, $resourceKey] = $this->createResource('Procedure');

            $procedure = Procedure::create(array_merge($resourceKey, $body['procedure']));

            $procedureKey = ['procedure_id' => $procedure->id];

            $this->createInstances(ProcedureIdentifier::class, $procedureKey, $body, 'identifier');
            $this->createInstances(ProcedureBasedOn::class, $procedureKey, $body, 'based_on');
            $this->createInstances(ProcedurePartOf::class, $procedureKey, $body, 'part_of');
            $this->createInstances(ProcedurePerformer::class, $procedureKey, $body, 'performer');
            $this->createInstances(ProcedureReason::class, $procedureKey, $body, 'reason');
            $this->createInstances(ProcedureBodySite::class, $procedureKey, $body, 'body_site');
            $this->createInstances(ProcedureReport::class, $procedureKey, $body, 'report');
            $this->createInstances(ProcedureComplication::class, $procedureKey, $body, 'complication');
            $this->createInstances(ProcedureFollowUp::class, $procedureKey, $body, 'follow_up');
            $this->createInstances(ProcedureNote::class, $procedureKey, $body, 'note');
            $this->createInstances(ProcedureFocalDevice::class, $procedureKey, $body, 'focal_device');
            $this->createInstances(ProcedureItemUsed::class, $procedureKey, $body, 'item_used');

            $this->createResourceContent(ProcedureResource::class, $resource);

            return response()->json($resource->procedure->first(), 201);
        });
    }
}
