<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FhirResource;

class IdentifierController extends Controller
{
    //
    public function getIdentifier(string $layanan, string $res_type)
    {
        if ($layanan == 'induk') {
            $organization = FhirResource::where('resourceType', 'Organization')
                ->where('id', config('app.organization_id'))
                ->first();
        } else {
            $organization = FhirResource::where('resourceType', 'Organization')
                ->where('id', config('app.' . $layanan . '_org_id'))
                ->first();
        }

        if (!$organization) {
            return response()->json([
                'message' => 'Organization not found'
            ], 404);
        }

        return [
            'system' => 'http://sys-ids.kemkes.go.id/' .$res_type.'/'.data_get($organization, 'id'),
            'use' => 'official',
            'value' => data_get($organization, 'name')
        ];
    }
}
