<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\FhirController;
use App\Models\Fhir\Resource;
use Illuminate\Support\Facades\Log;

class ResourceController extends FhirController
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
