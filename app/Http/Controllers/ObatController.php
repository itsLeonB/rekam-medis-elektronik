<?php

namespace App\Http\Controllers;
use App\Models\FhirResource;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                'id_medication' => data_get($medications, 'id'),
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
    public function getRequestStock(Request $request){
        $query = RequestStock::query();
        if ($request->query('name')) {
            $name = $request->query('name');
            $query->where('code.display', 'like', '%' . addcslashes($name, '%_') . '%');
        }

        if ($request->query('code')) {
            $code = $request->query('code');
            $query->where('code.code_kfa', 'like', '%' . addcslashes($code, '%_') . '%');
        }

        $datas = $query->paginate(15)->withQueryString();
        $formattedDatas = $datas->map(function ($data) {
            //tambahan
            $indexObat = FhirResource::where('resourceType', 'Medication')
                ->where('code.coding.0.code', data_get($data, 'code.code_kfa'))
                ->count();

            $opsi = $indexObat == 0 ? 'Tambahkan Obat' : 'Hapus';

            return [
                'code' => data_get($data, 'code.code_kfa'),
                'name' => data_get($data, 'code.display'),
                'opsi' => $opsi,
            ];
        });
        return [
            'obat' => [
                'current_page' => $datas->currentPage(),
                'data' => $formattedDatas,
                'first_page_url' => $datas->url(1),
                'from' => $datas->firstItem(),
                'last_page' => $datas->lastPage(),
                'last_page_url' => $datas->url($datas->lastPage()),
                'links' => $datas->links(),
                'next_page_url' => $datas->nextPageUrl() ?? null,
                'path' => $datas->path(),
                'per_page' => $datas->perPage(),
                'prev_page_url' => $datas->previousPageUrl() ?? null,
                'to' => $datas->lastItem(),
                'total' => $datas->total(),
            ],
        ];
    }
    public function deleteRequestStock($code){
        try {
            DB::beginTransaction();
            $data = RequestStock::where('code.code_kfa', $code)->firstOrFail();
            $data->delete();
            DB::commit();
            return response()->json(['message' => 'Data successfully deleted'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to save resource',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function show($medication_id)
    {
        try {
            $medication = FhirResource::where([
                ['resourceType', 'Medication'],
                ['id', $medication_id]
            ])->first();
            
            $data = [
                'code' => data_get($medication, 'code.coding.0.code'),
                'name' => data_get($medication, 'code.coding.0.display'),
                'status' => data_get($medication, 'status'),
                'extension' => data_get($medication, 'extension.0.valueCodeableConcept.coding.0.display'),
                'form' => data_get($medication, 'form.coding.0.display')
            ];
            return $data;
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Data tidak ditemukan',
                'message' => $th->getMessage()
            ], 404);
        }
    }
    public function checkRequest(){
        
        $data = RequestStock::count();
        if ($data > 0 ){
             return response()->json(['message' => 'Ada request stock dari dokter, silahkan klik Data Request Stok']);
        }
        else{
            return response()->json(['message' => '-']);
        }
       
    }
    
}
