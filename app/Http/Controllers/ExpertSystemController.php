<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FhirResource;
use App\Models\RulePeresepanObat;

class ExpertSystemController extends Controller
{
    //
    public function indexMedication(Request $request){
        $medications = FhirResource::where('resourceType', 'Medication')
            ->get(['id', 'code', 'name']);
        
        $medications = $medications->map(function ($item) {
            return [
                'id' => data_get($item, 'id'),
                'code' => data_get($item, 'code.coding.0.code'),
                'name' => data_get($item, 'code.coding.0.display')
            ];
        });

        return $medications;
    }
    public function rulePeresepanStore(Request $request)
    {
        $validated = $request->validate([
            'keluhan' => 'required|array',
            'keluhan.*' => 'required',
            'diagnosa' => 'required',
            'umur' => 'required',
        ]);

        $data = RulePeresepanObat::create($validated);

        return response()->json(['message' => 'Data berhasil disimpan', 'data' => $data], 201);
    }
}
