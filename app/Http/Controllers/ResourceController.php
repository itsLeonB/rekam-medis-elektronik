<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    public function index($resType)
    {
        if (!in_array($resType, config('app.resourceTypes'))) {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }

        return response()->json(DB::table($resType)->get(), 200);
    }

    public function store($resType, Request $request)
    {
        if (!in_array($resType, config('app.resourceTypes'))) {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }

        DB::beginTransaction();

        try {
            $id = DB::table($resType)->insertGetId($request->all());
            DB::commit();

            return response()->json(DB::table($resType)->find($id), 201);
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
            $resource = DB::table($resType)->where('id', $id)->get();

            if ($resource->isEmpty()) {
                return response()->json(['error' => 'Resource not found'], 404);
            }

            return response()->json(DB::table($resType)->where('id', $id)->get(), 200);
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
            DB::table($resType)->where('id', $id)->update($request->all());
            DB::commit();

            return response()->json(DB::table($resType)->where('id', $id)->get(), 200);
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
            DB::table($resType)->where('id', $id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
