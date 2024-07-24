<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $sort = $request->query('sort', 'updated_at');
        $direction = $request->query('direction', 'asc');


        $medicines = Medicine::where('name', 'like', "%{$search}%")
            ->orWhere('medicine_code', 'like', "%{$search}%")
            ->orderBy($sort, $direction)
            ->paginate(10);

        return response()->json($medicines);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'medicine_code' => 'string',
            'name' => 'string',
            'expiry_date' => 'date',
            'quantity' => 'integer',
            'package' => 'string',
            'uom' => 'string',
            'amount_per_package' => 'integer',
            'manufacturer' => 'string',
            'is_fast_moving' => 'boolean',
            'ingredients.*.ingredient_id' => 'string',
            'ingredients.*.ingredient_name' => 'string',
            'minimum_quantity' => 'integer',
            'dosage_form' => 'string',
            'prices.base_price' => 'numeric',
            'prices.purchase_price' => 'numeric',
            'prices.treatment_price_1' => 'numeric',
            'prices.treatment_price_2' => 'numeric',
            'prices.treatment_price_3' => 'numeric',
            'prices.treatment_price_4' => 'numeric',
            'prices.treatment_price_5' => 'numeric',
            'prices.treatment_price_6' => 'numeric',
            'prices.treatment_price_7' => 'numeric',
            'prices.treatment_price_8' => 'numeric',
            'prices.treatment_price_9' => 'numeric',
        ]);

        $validatedData['_id'] = Str::uuid();

        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        $validatedData['ingredients'] = json_encode($validatedData['ingredients']);
        $validatedData['prices'] = json_encode($validatedData['prices']);

        $medicine = Medicine::create($validatedData);

        return response()->json($medicine, 201);
    }

    public function show(Medicine $medicine)
    {
        return response()->json($medicine);
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validatedData = $request->validate([
            'medicine_code' => 'required|string',
            'name' => 'required|string',
            'expiry_date' => 'required|date',
            'quantity' => 'required|numeric',
            'package' => 'required|string',
            'uom' => 'required|string',
            'amount_per_package' => 'required|numeric',
            'manufacturer' => 'required|string',
            'is_fast_moving' => 'required|boolean',
            'ingredients' => 'required|array',
            'minimum_quantity' => 'required|numeric',
            'dosage_form' => 'required|string',
            'prices' => 'required|array',
        ]);

        $validatedData['updated_at'] = now();

        $medicine->update($validatedData);

        return response()->json($medicine);
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return response()->json(null, 204);
    }
}
