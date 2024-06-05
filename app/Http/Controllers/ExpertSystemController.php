<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FhirResource;
use App\Models\ExpertSystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
    public function getKeluhan($id)
    {
        //get Keluhan data sesuai Encounter
        $keluhan =FhirResource::where('resourceType', 'Condition')
                ->where('encounter.reference', 'Encounter/'.$id)
                ->where('category.0.coding.0.code', 'problem-list-item')    
                ->get();
        $result = $keluhan->map(function ($item) {
            return data_get($item, 'code.coding.0.code');
        })->toArray();
        return $result;
    }
    public function getDiagnosa($id)
    {
        //get diagnosa data sesuai Encounter
        $diagnosa =FhirResource::where('resourceType', 'Condition')
                ->where('encounter.reference', 'Encounter/'.$id)
                ->where('category.0.coding.0.code', 'encounter-diagnosis')
                ->where('verificationStatus.coding.0.code', 'confirmed')
                ->select('code')    
                ->first();

        $result = [
            'code' => data_get($diagnosa, 'code.coding.0.code'),
            'name' => data_get($diagnosa, 'code.coding.0.display'),
            ]
        ;
        return $result;
    }
    public function getMedicationReq($id)
    {
        $medReq =FhirResource::where('resourceType', 'MedicationRequest')
                ->where('encounter.reference', 'Encounter/'.$id)  
                ->get();
        
        $result = $medReq->map(function ($item) {
            return [
                'id' => data_get($item, 'id'),
                'display' => data_get($item, 'medicationReference.display'),
                'dosageInstruction' => data_get($item, 'dosageInstruction.0.text')
            ];
        })->toArray();

        return $result;
        
    }
    public function statusKehamilan($id){
        $result =  $this->getKeluhan($id);
        $code_pregnancy = "77386006";
        $exists = in_array($code_pregnancy, $result);
        return $exists;
    }
    public function kategoriUmur($id){
        $encounter = FhirResource::where('resourceType', 'Encounter')
            ->where('id', $id)
            ->get(['identifier']);

        $idPatient = null;
        foreach ($encounter as $item) {
            foreach ($item->identifier as $identifier) {
                $idPatient = $identifier['value'];
                break;
            }
        }
        $patient = FhirResource::where('resourceType', 'Patient')
            ->where('id', $idPatient)
            ->get(['birthDate']);

        $patient = $patient->map(function ($item) {
            return data_get($item, 'birthDate');
        });

        $date = Carbon::createFromFormat('Y-m-d', $patient[0]);
        $age = $date->diffInYears(Carbon::now());
        if ($age >= 12) {
            return 'dewasa';
        } elseif ($age >= 5) {
            return 'anak';
        } else {
           return 'balita';
        }
    }
    public function dataFisik($id){
        return 'data fisik';
    }
    public function getAlergi($id){
        $alergi =FhirResource::where('resourceType', 'AllergyIntolerance')
                ->where('encounter.reference', 'Encounter/'.$id)
                ->where('category', 'medication')  
                ->get();
        
        $result = $alergi->map(function ($item) {
            return data_get($item, 'code.coding.0.display');
        })->toArray();

        return $result;
    }
    public function rulePeresepanStore($id){
        try {
            DB::beginTransaction();
                $keluhan = $this->getKeluhan($id);
                $diagnosa = $this->getDiagnosa($id);
                $status_kehamilan = $this->statusKehamilan($id);
                $umur = $this->kategoriUmur($id);
                $resepObat = $this->getMedicationReq($id);
                $alergi = $this->getAlergi($id);
                $data = [
                        'keluhan' => $keluhan,
                        'diagnosa' => $diagnosa,
                        'statusKehamilan' => $status_kehamilan,
                        'umur' => $umur,
                        'alergi' => $alergi,
                        'resepObat' => $resepObat
                    ];
                    ExpertSystem::insert($data);

            DB::commit();
            
            return response()->json([
                'message' => 'Data inserted successfully.',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
                Log::error('Failed to save resource: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to save resource',
                    'message' => $e->getMessage()
                ], 500);
        }
    }
    public function rulePeresepanShow($rule, $id){
        $req_keluhan =  $this->getKeluhan($id);

        $req_kehamilan = $this->statusKehamilan($id);
        $req_umur = $this->kategoriUmur($id);
        $req_alergi = $this->getAlergi($id);
        if ($rule=='diagnosa') {
            $result = ExpertSystem::where('keluhan', 'all', $req_keluhan)
                    ->where('umur', $req_umur)
                    ->where('statusKehamilan', $req_kehamilan)
                    ->pluck('diagnosa');
                    if ($result->isEmpty()) {
                         return response('Belum ada rekomendasi dari data keluhan sebelumnya');
                    }
                    else {
                        return $req_alergi;
                    }
           
        } else {
            $result = ExpertSystem::where("keluhan", 'all', $req_keluhan)
                    ->where('umur', $req_umur)
                    ->where('statusKehamilan', $req_kehamilan)
                    ->whereNot('alergi', $req_alergi)
                    ->pluck('resepObat');
                    
                     if ($result->isEmpty()) {
                         return response('Belum ada rekomendasi dari data keluhan sebelumnya');
                    }
                    else {
                        return $req_alergi;
                    }
        }
        
    }
}