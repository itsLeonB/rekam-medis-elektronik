<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    public function indexResource($res_type)
    {
        return response()->json(Resource::where('res_type', '=', $res_type)->get(), 200);
    }

    public function getResource($res_type, $satusehat_id)
    {
        try {
            return response()->json(Resource::where([
                ['satusehat_id', '=', $satusehat_id],
                ['res_type', '=', $res_type]
            ])->firstOrFail()->$res_type->first(), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }
}
