<?php

namespace App\Http\Controllers;
use App\Models\FhirResource;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $inventoryItem = FhirResource::where('resourceType', 'InventoryItem');
        
        if ($request->query('name')) {
            $inventoryItem = $inventoryItem->where('name.text', 'like', '%' . addcslashes($request->query('name'), '%_') . '%');
            return $inventoryItem;
        }
        $inventoryItem = $inventoryItem->paginate(15)->withQueryString();

        $formattedInventoryItem = $inventoryItem->map(function ($inventoryItem) {
            return [
                'code' => data_get($inventoryItem, 'code.coding.0.code'),
                'name' => data_get($inventoryItem, 'code.coding.0.display'),
            ];
        });

        return [
            'inventory' => [
                'current_page' => $inventoryItem->currentPage(),
                'data' => $formattedInventoryItem,
                'first_page_url' => $inventoryItem->url(1),
                'from' => $inventoryItem->firstItem(),
                'last_page' => $inventoryItem->lastPage(),
                'last_page_url' => $inventoryItem->url($inventoryItem->lastPage()),
                'links' => $inventoryItem->links(),
                'next_page_url' => $inventoryItem->nextPageUrl() ?? null,
                'path' => $inventoryItem->path(),
                'per_page' => $inventoryItem->perPage(),
                'prev_page_url' => $inventoryItem->previousPageUrl() ?? null,
                'to' => $inventoryItem->lastItem(),
                'total' => $inventoryItem->total(),
            ],
        ];
    }
}
