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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'display' => 'required|string|max:255',
            'price.currency' => 'required|string|max:3',
            'price.value' => 'required|numeric|min:0',
        ]);


        DB::beginTransaction();
        try {

            $postData = [
                'code' => $validatedData['code'],
                'display' => $validatedData['display'],
                'price' => [
                    'currency' => $validatedData['price']['currency'],
                    'value' => $validatedData['price']['value']
                ]
            ];

            $product = new ServicePrice($postData);
            $product->save();
            DB::commit();
            return response()->json($product, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Gagal membuat item',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $item = ServicePrice::where('code', $id)->first();
            return response()->json($item, 200);
        } catch (Throwable $th) {
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
            $item = ServicePrice::where('code', $item_id)->first();

            $updateData = [
                'code' => $request->input('code'),
                'display' => $request->input('display'),
                'price' => [
                    'currency' => $request->input('price.currency'),
                    'value' => $request->input('price.value')
                ]
            ];
            $item->update($updateData);
            DB::commit();
            return response()->json($item, 200);
        } catch (\Throwable $th) {
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
