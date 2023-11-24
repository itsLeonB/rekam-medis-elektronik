<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Http\Resources\ClinicalImpressionResource;
use App\Http\Resources\CompositionResource;
use App\Http\Resources\ConditionResource;
use App\Http\Resources\EncounterResource;
use App\Http\Resources\MedicationDispenseResource;
use App\Http\Resources\MedicationRequestResource;
use App\Http\Resources\MedicationResource;
use App\Http\Resources\ObservationResource;
use App\Http\Resources\ProcedureResource;
use App\Http\Resources\ServiceRequestResource;
use App\Http\Resources\SpecimenResource;
use App\Models\Resource;
use Tests\Traits\FhirTest;

class TestController extends Controller
{
    use FhirTest;

    public function testSpecimenResource($satusehat_id)
    {
        return response()->json(new SpecimenResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'Specimen']
        ])->firstOrFail()), 200);
    }


    public function testServiceRequestResource($satusehat_id)
    {
        return response()->json(new ServiceRequestResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'ServiceRequest']
        ])->firstOrFail()), 200);
    }

    public function testClinicalImpressionResource($satusehat_id)
    {
        return response()->json(new ClinicalImpressionResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'ClinicalImpression']
        ])->firstOrFail()), 200);
    }

    public function testCompositionResource($satusehat_id)
    {
        return response()->json(new CompositionResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'Composition']
        ])->firstOrFail()), 200);
    }

    public function testMedicationDispenseResource($satusehat_id)
    {
        return response()->json(new MedicationDispenseResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'MedicationDispense']
        ])->firstOrFail()), 200);
    }

    public function testMedicationRequestResource($satusehat_id)
    {
        return response()->json(new MedicationRequestResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'medicationrequest']
        ])->firstOrFail()), 200);
    }

    public function testMedicationResource($satusehat_id)
    {
        return response()->json(new MedicationResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'medication']
        ])->firstOrFail()), 200);
    }

    public function testProcedureResource($satusehat_id)
    {
        return response()->json(new ProcedureResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'procedure']
        ])->firstOrFail()), 200);
    }

    public function testObservationResource($satusehat_id)
    {
        return response()->json(new ObservationResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'observation']
        ])->firstOrFail()), 200);
    }

    public function testAllergyIntoleranceResource($satusehat_id)
    {
        return response()->json(new AllergyIntoleranceResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'allergyintolerance']
        ])->firstOrFail()), 200);
    }
}
