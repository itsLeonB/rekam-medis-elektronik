<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ServicePriceController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->query('name');

        $items = ServicePrice::when($name, function ($query) use ($name) {
            return $query->where('display', 'like', '%' . addcslashes($name, '%_') . '%');
        })
            ->paginate(15)
            ->withQueryString();

        return response()->json(['items' => $items], 200);
    }

    public function store()
    {
    }

    public function show($id) 
    {
        try {
            $item = ServicePrice::where('code',$id)->first();
            return response()->json($item, 200);
        } catch (Throwable $th){
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'item tidak ditemukan',
                'message' => $th->getMessage()
            ], 404);
        }   
    }

    public function update(Request $request, $item_id)
    {
        DB::beginTransaction();
        try {
            $item = ServicePrice::where('code',$item_id)->first();

            $updateData = [
                'code'=> $request->input('code'),
                'display'=>$request->input('display'),
                'price'=> [
                    'currency'=>$request->input('price.currency'),
                    'value'=>$request->input('price.value')
                ]
            ];
            $item->update($updateData);
            DB::commit();
            return response()->json($item, 200);
        }catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Gagal memperbarui item',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy()
    {
    }
}
