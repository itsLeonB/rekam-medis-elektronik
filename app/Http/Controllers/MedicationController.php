<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationRequest;
use App\Http\Resources\MedicationResource;
use App\Models\Medication;
use App\Models\MedicationIdentifier;
use App\Models\MedicationIngredient;
use App\Models\Resource;
use App\Models\ResourceContent;
use App\Services\FhirService;

class MedicationController extends Controller
{
    public function postMedication(MedicationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            $resource = Resource::create([
                'res_type' => 'Medication',
                'res_ver' => 1,
            ]);

            $resourceKey = ['resource_id' => $resource->id];

            $medication = Medication::create(array_merge($resourceKey, $body['medication']));

            $medicationKey = ['medication_id' => $medication->id];

            $this->createInstances(MedicationIdentifier::class, $medicationKey, $body, 'identifier');
            $this->createInstances(MedicationIngredient::class, $medicationKey, $body, 'ingredient');

            $resourceData = new MedicationResource($resource);
            $resourceText = json_encode($resourceData);

            ResourceContent::create([
                'resource_id' => $resource->id,
                'res_ver' => 1,
                'res_text' => $resourceText,
            ]);

            return response()->json($resource->medication->first(), 201);
        });
    }
}
