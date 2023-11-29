<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Http\Resources\ClinicalImpressionResource;
use App\Http\Resources\CompositionResource;
use App\Http\Resources\ImagingStudyResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\MedicationDispenseResource;
use App\Http\Resources\MedicationRequestResource;
use App\Http\Resources\MedicationResource;
use App\Http\Resources\ObservationResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\ProcedureResource;
use App\Http\Resources\ServiceRequestResource;
use App\Http\Resources\SpecimenResource;
use App\Models\Resource;

class TestController extends Controller
{
    public function testOrganizationResource($satusehat_id)
    {
        return response()->json(new OrganizationResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'Organization']
        ])->firstOrFail()), 200);
    }


    public function testLocationResource($satusehat_id)
    {
        return response()->json(new LocationResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'Location']
        ])->firstOrFail()), 200);
    }


    public function testImagingStudyResource($satusehat_id)
    {
        return response()->json(new ImagingStudyResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'ImagingStudy']
        ])->firstOrFail()), 200);
    }

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
