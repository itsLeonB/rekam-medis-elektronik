<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EncounterFormController extends Controller
{
    public function indexPractitioner()
    {
        $practitioners = DB::table('practitioner')
            ->get(['id', 'name']);

        $practitioners = $practitioners->map(function ($item) {
            return [
                'satusehat_id' => data_get($item, 'id'),
                'name' => data_get($item, 'name.0.text'),
            ];
        });

        return $practitioners;
    }

    public function indexLocation()
    {
        $locations = DB::table('location')
            ->get(['id', 'identifier', 'name', 'serviceClass']);

        $locations = $locations->map(function ($item) {
            return [
                'satusehat_id' => data_get($item, 'id'),
                'identifier' => data_get($item, 'identifier.0.value'),
                'name' => data_get($item, 'name'),
                'serviceClass' => data_get($item, 'serviceClass.valueCodeableConcept.coding.0.display'),
            ];
        });

        return $locations;
    }

    public function getOrganization(string $layanan)
    {
        if ($layanan == 'induk') {
            $organization = DB::table('organization')
                ->where('id', config('app.organization_id'))
                ->first();
        } else {
            $organization = DB::table('organization')
                ->where('id', config('app.' . $layanan . '_org_id'))
                ->first();
        }

        if (!$organization) {
            return response()->json([
                'message' => 'Organization not found'
            ], 404);
        }

        return [
            'reference' => 'Organization/' . data_get($organization, 'id'),
            'display' => data_get($organization, 'name')
        ];
    }
}
