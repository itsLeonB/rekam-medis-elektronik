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
        // return $medications;

        if ($request->query('name')) {
            $name = $request->query('name');
            $medications = $medications->where('code.coding.0.display', 'like', '%' . addcslashes($name, '%_') . '%');
        }
        if ($request->query('form')) {
            $form = $request->query('form');
            $medications = $medications->where('form.coding.0.display', 'like', '%' . addcslashes($form, '%_') . '%');
        }
        $medications = $medications->paginate(15)->withQueryString();

        $formattedMedications = $medications->map(function ($medications) {
            return [
                'code' => data_get($medications, 'code.coding.0.code'),
                'name' => data_get($medications, 'code.coding.0.display'),
                'status' => data_get($medications, 'status'),
                'form' => data_get($medications, 'form.coding.0.display')
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
