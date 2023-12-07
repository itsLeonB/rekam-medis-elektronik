<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Http\Resources\ClinicalImpressionResource;
use App\Http\Resources\CompositionResource;
use App\Http\Resources\ConditionResource;
use App\Http\Resources\EncounterResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\MedicationRequestResource;
use App\Http\Resources\MedicationResource;
use App\Http\Resources\ObservationResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\ProcedureResource;
use App\Models\Resource;

class TestController extends Controller
{
    public function testClinicalImpression()
    {
        return response()
            ->json(new ClinicalImpressionResource(Resource::where('res_type', '=', 'ClinicalImpression')
                ->firstOrFail()), 200);
    }


    public function testAllergyIntolerance()
    {
        return response()
            ->json(new AllergyIntoleranceResource(Resource::where('res_type', '=', 'AllergyIntolerance')
                ->firstOrFail()), 200);
    }

    public function testComposition()
    {
        return response()
            ->json(new CompositionResource(Resource::where('res_type', '=', 'Composition')
                ->firstOrFail()), 200);
    }

    public function testMedicationRequest()
    {
        return response()
            ->json(new MedicationRequestResource(Resource::where('res_type', '=', 'MedicationRequest')
                ->firstOrFail()), 200);
    }


    public function testMedication()
    {
        return response()
            ->json(new MedicationResource(Resource::where('res_type', '=', 'Medication')
                ->firstOrFail()), 200);
    }


    public function testProcedure()
    {
        return response()
            ->json(new ProcedureResource(Resource::where('res_type', '=', 'Procedure')
                ->firstOrFail()), 200);
    }


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
