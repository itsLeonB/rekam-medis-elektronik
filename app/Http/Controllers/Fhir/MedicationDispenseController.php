<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationDispenseRequest;
use App\Http\Resources\MedicationDispenseResource;
use App\Services\FhirService;

class MedicationDispenseController extends Controller
{
    /**
     * Store a new medication dispense.
     *
     * @param MedicationDispenseRequest $request The request object.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function store(MedicationDispenseRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('MedicationDispense');
            $medicationDispense = $resource->medicationDispense()->create($body['medicationDispense']);
            $this->createChildModels($medicationDispense, $body, ['identifier', 'partOf', 'performer', 'authorizingPrescription']);
            $this->createNestedInstances($medicationDispense, 'dosage', $body, ['additionalInstruction', 'doseRate']);
            $this->createNestedInstances($medicationDispense, 'substitution', $body, ['reason', 'responsibleParty']);
            $this->createResourceContent(MedicationDispenseResource::class, $resource);
            return response()->json($resource->medicationDispense->first(), 201);
        });
    }


    /**
     * Update a medication dispense resource.
     *
     * @param MedicationDispenseRequest $request The request object.
     * @param int $res_id The resource ID.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function update(MedicationDispenseRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $medicationDispense = $resource->medicationDispense()->first();
            $medicationDispense->update($body['medicationDispense']);
            $dispenseId = $medicationDispense->id;
            $this->updateChildModels($medicationDispense, $body, ['identifier', 'partOf', 'performer', 'authorizingPrescription'], 'dispense_id', $dispenseId);
            $this->updateNestedInstances($medicationDispense, 'dosage', $body, 'dispense_id', $dispenseId, ['additionalInstruction', 'doseRate'], 'med_disp_dose_id');
            $this->updateNestedInstances($medicationDispense, 'substitution', $body, 'dispense_id', $dispenseId, ['reason', 'responsibleParty'], 'med_disp_subs_id');
            $this->createResourceContent(MedicationDispenseResource::class, $resource);
            return response()->json($resource->medicationDispense->first(), 200);
        });
    }
}
