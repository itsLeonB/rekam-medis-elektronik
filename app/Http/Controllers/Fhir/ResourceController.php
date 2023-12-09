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
            Log::error('Invalid resource type requested: ' . $res_type);
            return response()->json(['error' => 'Invalid resource type.'], 400);
        }

        return response()->json(Resource::where('res_type', '=', $res_type)->get(), 200);
    }
}
