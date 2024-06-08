<?php

namespace App\Http\Controllers;
use App\Models\FhirResource;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    //
    public function index(Request $request)
    {
        $medications = FhirResource::where('resourceType', 'Medication');
        
        if ($request->query('name')) {
            $medications = $medications->where('name.text', 'like', '%' . addcslashes($request->query('name'), '%_') . '%');
            return $medications;
        }
        $medications = $medications->paginate(15)->withQueryString();

        $formattedMedications = $medications->map(function ($medications) {
            return [
                'code' => data_get($medications, 'code.coding.0.code'),
                'name' => data_get($medications, 'code.coding.0.display'),
                'status' => data_get($medications, 'status'),
                'form' => data_get($medications, 'form.coding.0.display'),
                'extension'  => data_get($medications, 'extension.0.valueCodeableConcept.coding.0.display'),
            ];
        });

        return [
            'obat' => [
                'current_page' => $medications->currentPage(),
                'data' => $formattedMedications,
                'first_page_url' => $medications->url(1),
                'from' => $medications->firstItem(),
                'last_page' => $medications->lastPage(),
                'last_page_url' => $medications->url($medications->lastPage()),
                'links' => $medications->links(),
                'next_page_url' => $medications->nextPageUrl() ?? null,
                'path' => $medications->path(),
                'per_page' => $medications->perPage(),
                'prev_page_url' => $medications->previousPageUrl() ?? null,
                'to' => $medications->lastItem(),
                'total' => $medications->total(),
            ],
        ];
    }
}
