<?php

namespace App\Http\Controllers;

use App\Models\FhirResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    public function index($resType)
    {
        if (!in_array($resType, config('app.resourceTypes'))) {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }

        return response()->json(FhirResource::where('resourceType', $resType)->get(), 200);
    }

    public function store($resType, Request $request)
    {
        if (!in_array($resType, config('app.resourceTypes'))) {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }

        DB::beginTransaction();

        try {
            $res = FhirResource::create($request->all());
            DB::commit();
            return response()->json($res, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($resType, $id)
    {
        if (!in_array($resType, config('app.resourceTypes'))) {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }

        try {
            $resource = FhirResource::where([
                ['resourceType', $resType],
                ['id', $id]
            ])->first();
            return response()->json($resource, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($resType, $id, Request $request)
    {
        if (!in_array($resType, config('app.resourceTypes'))) {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }

        DB::beginTransaction();

        try {
            $resource = FhirResource::where([
                ['resourceType', $resType],
                ['id', $id]
            ])->update($request->all());
            DB::commit();
            return response()->json($resource, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($resType, $id)
    {
        if (!in_array($resType, config('app.resourceTypes'))) {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }

        DB::beginTransaction();

        try {
            FhirResource::where([
                ['resourceType', $resType],
                ['id', $id]
            ])->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
