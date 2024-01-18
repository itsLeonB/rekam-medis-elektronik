<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Location;
use App\Models\Fhir\Resources\Medication;
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

    public function indexMedication()
    {
        $medications = Medication::with(['code', 'form', 'medicationType'])->paginate(15, ['id', 'resource_id']);

        $medList = $medications->map(function ($item) {
            return [
                'satusehat_id' => data_get($item, 'resource.satusehat_id'),
                'code' => [
                    'system' => data_get($item, 'code.coding.0.system'),
                    'code' => data_get($item, 'code.coding.0.code'),
                    'display' => data_get($item, 'code.coding.0.display'),
                ],
                'form' => [
                    'system' => data_get($item, 'form.coding.0.system'),
                    'code' => data_get($item, 'form.coding.0.code'),
                    'display' => data_get($item, 'form.coding.0.display'),
                ],
                'medicationType' => [
                    'system' => data_get($item, 'medicationType.valueCodeableConcept.coding.0.system'),
                    'code' => data_get($item, 'medicationType.valueCodeableConcept.coding.0.code'),
                    'display' => data_get($item, 'medicationType.valueCodeableConcept.coding.0.display'),
                ],
            ];
        });

        return [
            'current_page' => $medications->currentPage(),
                'data' => $medList,
                'first_page_url' => $medications->url(1),
                'from' => $medications->firstItem(),
                'last_page' => $medications->lastPage(),
                'last_page_url' => $medications->url($medications->lastPage()),
                'links' => $medications->links(),
                'next_page_url' => $medications->nextPageUrl() ?? null,
                'path' => $medications->path(),
                'per_page' => $medications->perPage(),
                'prev_page_url' => $medications->previousPageUrl() ?? null,
                'to' => $medications->lastItem(),
                'total' => $medications->total(),
        ];
    }
}
