<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FhirResource;

class MedicationRequestController extends Controller
{
    //
    public function searchMedication(Request $request){
        $search = $request->query('search');
        $medications = FhirResource::where('resourceType', 'Medication')
        ->when($search, function ($query, $search) {
            return $query->where('code.coding.0.display', 'like', '%' . $search . '%');
        })    
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
    public function showForConditionPatient($section, $id){

        if ($section == 'keluhan') {
            $data = FhirResource::where('resourceType', 'Condition')
                    ->where('encounter.reference', 'Encounter/'. $id)
                    ->where('code.coding.0.system', 'http://snomed.info/sct')
                    ->get();
        }elseif ($section == 'diagnosa') {
            $data = FhirResource::where('resourceType', 'Condition')
                    ->where('encounter.reference', 'Encounter/'. $id)
                    ->where('code.coding.0.system', 'http://hl7.org/fhir/sid/icd-10')
                    ->get();
        }else{
            $data = FhirResource::where('resourceType', 'AllergyIntolerance')
                    ->where('encounter.reference', 'Encounter/'. $id)
                    ->get();
        }
        return response()->json($data);
    }
}
