<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicineController extends Controller
{
    public function index()
    {
        try {
            // Fetch all medicines from the 'medicines' table
            $medicines = DB::table('medicines')
                            ->get();

            // Return the JSON response
            return response()->json($medicines);
        } catch (\Exception $e) {
            // Log any errors
            Log::error("Error fetching medicines: " . $e->getMessage());
            
            // Return an error response
            return response()->json(['error' => 'Could not fetch medicines.'], 500);
        }
    }

    public function show($medicine_code)
    {
        $medicine = DB::table('medicines')
                        ->leftJoin('medicine_prices', 'medicines.medicine_prices_id', '=', 'medicine_prices.id')
                        ->select('medicines.*', 'medicine_prices.price')
                        ->where('medicine_code', $medicine_code)
                        ->first();

        if (!$medicine) {
            return response()->json(['error' => 'Medicine not found'], 404);
        }

        return response()->json($medicine);
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_code' => 'required|string|unique:medicines',
            'name' => 'required|string',
            'expiry_date' => 'required|date',
            'quantity' => 'required|integer',
            'package' => 'required|string',
            'uom' => 'required|string',
            'amount_per_package' => 'required|integer',
            'manufacturer' => 'required|string',
            'is_fast_moving' => 'nullable|boolean',
            'ingredients' => 'required|array',
            'minimum_quantity' => 'required|integer',
            'dosage_form' => 'required|array',
            'medicine_prices_id' => 'required|exists:medicine_prices,id',
        ]);

        $medicine = DB::table('medicines')->insertGetId($request->all());

        $insertedMedicine = DB::table('medicines')
                                ->leftJoin('medicine_prices', 'medicines.medicine_prices_id', '=', 'medicine_prices.id')
                                ->select('medicines.*', 'medicine_prices.price')
                                ->where('medicine_code', $request->medicine_code)
                                ->first();

        return response()->json($insertedMedicine, 201);
    }

    public function update(Request $request, $medicine_code)
    {
        $request->validate([
            'name' => 'required|string',
            'expiry_date' => 'required|date',
            'quantity' => 'required|integer',
            'package' => 'required|string',
            'uom' => 'required|string',
            'amount_per_package' => 'required|integer',
            'manufacturer' => 'required|string',
            'is_fast_moving' => 'nullable|boolean',
            'ingredients' => 'required|array',
            'minimum_quantity' => 'required|integer',
            'dosage_form' => 'required|array',
            'medicine_prices_id' => 'required|exists:medicine_prices,id',
        ]);

        $affected = DB::table('medicines')
                        ->where('medicine_code', $medicine_code)
                        ->update($request->all());

        if ($affected) {
            $updatedMedicine = DB::table('medicines')
                                    ->leftJoin('medicine_prices', 'medicines.medicine_prices_id', '=', 'medicine_prices.id')
                                    ->select('medicines.*', 'medicine_prices.price')
                                    ->where('medicine_code', $medicine_code)
                                    ->first();

            return response()->json($updatedMedicine);
        }

        return response()->json(['error' => 'Medicine not found or update failed'], 404);
    }

    public function destroy($medicine_code)
    {
        $deleted = DB::table('medicines')->where('medicine_code', $medicine_code)->delete();

        if ($deleted) {
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'Medicine not found or delete failed'], 404);
    }
}
