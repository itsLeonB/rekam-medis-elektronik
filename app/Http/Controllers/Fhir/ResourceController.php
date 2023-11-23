<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    public function index($res_type)
    {
        // Validate the resource type
        if (!in_array($res_type, Resource::VALID_RESOURCE_TYPES)) {
            return response()->json(['error' => 'Invalid resource type.'], 400);
        }

        return response()->json(Resource::where('res_type', '=', $res_type)->get(), 200);
    }

    public function show($res_type, $res_id)
    {
        if (!in_array($res_type, Resource::VALID_RESOURCE_TYPES)) {
            return response()->json(['error' => 'Invalid resource type.'], 400);
        }

        try {
            return response()->json(Resource::where([
                ['id', '=', $res_id],
                ['res_type', '=', $res_type]
            ])->firstOrFail()->$res_type->first(), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }
}
