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
            [$resource, $resourceKey] = $this->createResource('Medication');

            $medication = Medication::create(array_merge($resourceKey, $body['medication']));

            $medicationKey = ['medication_id' => $medication->id];

            $this->createInstances(MedicationIdentifier::class, $medicationKey, $body, 'identifier');
            $this->createInstances(MedicationIngredient::class, $medicationKey, $body, 'ingredient');

            $this->createResourceContent(MedicationResource::class, $resource);

            return response()->json($resource->medication->first(), 201);
        });
    }
}
