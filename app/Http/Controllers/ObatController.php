<?php

namespace App\Http\Controllers;

use App\Models\FhirResource;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // List medications and inventory items
    public function index(Request $request)
    {
        $medications = FhirResource::where('resourceType', 'Medication');

        if ($request->query('name')) {
            $medications->where('resourceData.code.coding.0.display', 'like', '%' . addcslashes($request->query('name'), '%_') . '%');
        }

        $medications = $medications->paginate(15)->withQueryString();

        $formattedMedications = $medications->map(function ($medication) {
            return [
                'id' => $medication->id,
                'code' => data_get($medication, 'resourceData.code.coding.0.code'),
                'name' => data_get($medication, 'resourceData.code.coding.0.display'),
                'status' => data_get($medication, 'resourceData.status'),
                'form' => data_get($medication, 'resourceData.form.coding.0.display'),
                'extension' => data_get($medication, 'resourceData.extension.0.valueCodeableConcept.coding.0.display'),
            ];
        });

        return response()->json([
            'medications' => [
                'current_page' => $medications->currentPage(),
                'data' => $formattedMedications,
                'first_page_url' => $medications->url(1),
                'from' => $medications->firstItem(),
                'last_page' => $medications->lastPage(),
                'last_page_url' => $medications->url($medications->lastPage()),
                'links' => $medications->linkCollection(),
                'next_page_url' => $medications->nextPageUrl(),
                'path' => $medications->path(),
                'per_page' => $medications->perPage(),
                'prev_page_url' => $medications->previousPageUrl(),
                'to' => $medications->lastItem(),
                'total' => $medications->total(),
            ],
        ]);
    }

    // Add a new medication or inventory item
    public function store(Request $request)
    {
        $request->validate([
            'resourceType' => 'required|in:Medication,InventoryItem',
            'resourceData.code.coding.0.code' => 'required',
            'resourceData.code.coding.0.display' => 'required',
            'resourceData.status' => 'required',
        ]);

        if ($request->resourceType == 'InventoryItem') {
            $request->validate([
                'resourceData.quantity.value' => 'required|numeric',
                'resourceData.quantity.unit' => 'required',
            ]);
        }

        $resource = FhirResource::create([
            'resourceType' => $request->resourceType,
            'resourceData' => $request->resourceData,
        ]);

        return response()->json($resource, 201);
    }

    // Update an existing medication or inventory item
    public function update(Request $request, $id)
    {
        $resource = FhirResource::findOrFail($id);

        $request->validate([
            'resourceData.code.coding.0.code' => 'required',
            'resourceData.code.coding.0.display' => 'required',
            'resourceData.status' => 'required',
        ]);

        if ($resource->resourceType == 'InventoryItem') {
            $request->validate([
                'resourceData.quantity.value' => 'required|numeric',
                'resourceData.quantity.unit' => 'required',
            ]);
        }

        $resource->update([
            'resourceData' => $request->resourceData,
        ]);

        return response()->json($resource);
    }

    // Delete a medication or inventory item
    public function destroy($id)
    {
        $resource = FhirResource::findOrFail($id);
        $resource->delete();

        return response()->json(null, 204);
    }
}
