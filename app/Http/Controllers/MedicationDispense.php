<?php

namespace App\Http\Controllers;

use App\Models\FhirResource;
use Illuminate\Http\Request;

class MedicationDispense extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return FhirResource::where('resourceType', 'MedicationRequest')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($medicationReq_id)
    {
        try {
            $medicationReq = FhirResource::where([
                ['resourceType', 'MedicationRequest'],
                ['id', $medicationReq_id]
            ])->first();
            
            $data = [
                'id' => data_get($medicationReq, 'id'),
                'requester' => data_get($medicationReq, 'requester.display'),
                'subject' => data_get($medicationReq, 'requester.reference'),
                'medication' => data_get($medicationReq, 'medicationReference.display'),
                'quantity' => data_get($medicationReq, 'dispenseRequest.quantity.value'),
                'uom' => data_get($medicationReq, 'dispenseRequest.quantity.unit'),
                'uom_code' => data_get($medicationReq, 'dispenseRequest.quantity.code'),
                'category' => data_get($medicationReq, 'category.0.coding.0.display'),
                'patient' => data_get($medicationReq, 'subject.display'),
                'validStart' => data_get($medicationReq, 'dispenseRequest.validityPeriod.start'),
                'validEnd' => data_get($medicationReq, 'dispenseRequest.validityPeriod.end'),
                'medicationReferenceName' => data_get($medicationReq, 'medicationReference.display'),
                'medicationReferenceId' => data_get($medicationReq, 'medicationReference.reference'),
                'encounter' => data_get($medicationReq, 'encounter.reference'),
            ];
            return $data;
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Data tidak ditemukan',
                'message' => $th->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FhirResource $fhirResource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FhirResource $fhirResource)
    {
        //
    }
}
