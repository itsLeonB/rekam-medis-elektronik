<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestStockController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code.code_kfa' => 'required',
            'code.display' => 'required',
            'prioritas' => 'required|string',
            'stok' => 'required|integer',
            'satuan' => 'required|string',
            'note' => 'nullable|string'
        ]);
        try {
            DB::beginTransaction();
                $created = RequestStock::create([
                'code' => [
                        'code_kfa' => $validatedData['code']['code_kfa'],
                        'display' => $validatedData['code']['display'],
                ],
                'prioritas' => $validatedData['prioritas'],
                'stok' => $validatedData['stok'],
                'satuan' => $validatedData['satuan'],
                'note' => $validatedData['note']
                ]);
            DB::commit();
            return response()->json(['message' => 'Data stored successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to save resource',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
