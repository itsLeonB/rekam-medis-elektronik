<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Location;
use App\Models\Fhir\Resources\Practitioner;

class EncounterFormController extends Controller
{
    public function indexPractitioner()
    {
        $practitioners = Practitioner::with(['name', 'resource'])
            ->get(['id', 'resource_id']);

        $practitioners = $practitioners->map(function ($item) {
            return [
                'satusehat_id' => data_get($item, 'resource.satusehat_id'),
                'name' => data_get($item, 'name.0.text'),
            ];
        });

        return $practitioners;
    }

    public function indexLocation()
    {
        $locations = Location::with(['resource', 'identifier', 'serviceClass'])
        ->get(['id', 'resource_id', 'name']);

        $locations = $locations->map(function ($item) {
            return [
                'satusehat_id' => data_get($item, 'resource.satusehat_id'),
                'identifier' => data_get($item, 'identifier.0.value'),
                'name' => data_get($item, 'name'),
                'serviceClass' => data_get($item, 'serviceClass.valueCodeableConcept.coding.0.display'),
            ];
        });

        return $locations;
    }

    public function getOrganization(string $layanan)
    {
        $organization = Resource::where([
            ['res_type', 'Organization'],
            ['satusehat_id', config('app.' . $layanan . '_org_id')],
        ])->first();

        if (!$organization) {
            return response()->json([
                'message' => 'Organization not found'
            ], 404);
        }

        return [
            'reference' => 'Organization/' . data_get($organization, 'satusehat_id'),
            'display' => data_get($organization, 'organization.name')
        ];
    }
}
