<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::all();
        return response()->json($medicines);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'medicine_code' => 'required|string',
            'name' => 'required|string',
            'expiry_date' => 'required|date',
            'quantity' => 'required|integer',
            'package' => 'string',
            'uom' => 'string',
            'amount_per_package' => 'integer',
            'manufacturer' => 'string',
            'is_fast_moving' => 'boolean',
            'ingredients' => 'array',
            'minimum_quantity' => 'required|integer',
            'dosage_form' => 'string',
            'prices' => 'required|array',
        ]);

        $validatedData['_id'] = Str::uuid();
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

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
            'quantity' => 'required|integer',
            'package' => 'required|string',
            'uom' => 'required|string',
            'amount_per_package' => 'required|integer',
            'manufacturer' => 'required|string',
            'is_fast_moving' => 'required|boolean',
            'ingredients' => 'required|array',
            'minimum_quantity' => 'required|integer',
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
