<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConditionResource;
use App\Http\Resources\EncounterResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\ObservationResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\PatientResource;
use App\Models\Resource;

class TestController extends Controller
{
    public function testObservation()
    {
        return response()
            ->json(new ObservationResource(Resource::where('res_type', '=', 'Observation')
                ->firstOrFail()), 200);
    }


    public function testCondition()
    {
        return response()
            ->json(new ConditionResource(Resource::where('res_type', '=', 'Condition')
                ->firstOrFail()), 200);
    }


    public function testEncounter()
    {
        return response()
            ->json(new EncounterResource(Resource::where('res_type', '=', 'Encounter')
                ->firstOrFail()), 200);
    }


    public function testOrganization()
    {
        return response()
            ->json(new OrganizationResource(Resource::where('res_type', '=', 'Organization')
                ->firstOrFail()), 200);
    }


    public function testLocation()
    {
        return response()
            ->json(new LocationResource(Resource::where('res_type', '=', 'Location')
                ->firstOrFail()), 200);
    }


    public function testPatient()
    {
        return response()
            ->json(new PatientResource(Resource::where('res_type', '=', 'Patient')
                ->firstOrFail()), 200);
    }

}
