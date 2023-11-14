<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestBasedOn;
use App\Models\ServiceRequestBodySite;
use App\Models\ServiceRequestCategory;
use App\Models\ServiceRequestIdentifier;
use App\Models\ServiceRequestInsurance;
use App\Models\ServiceRequestLocation;
use App\Models\ServiceRequestNote;
use App\Models\ServiceRequestOrderDetail;
use App\Models\ServiceRequestPerformer;
use App\Models\ServiceRequestRelevantHistory;
use App\Models\ServiceRequestSupportingInfo;

class ServiceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceRequests = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'ServiceRequest')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($serviceRequests as $sr) {
            $resContent = json_decode($sr->res_text, true);
            $requisition = returnIdentifier(returnAttribute($resContent, ['requisition']), 'requisition');
            $code = returnCodeableConcept(returnAttribute($resContent, ['code']), 'code');
            $arr = [
                'resource_id' => $sr->id,
                'status' => returnAttribute($resContent, ['status'], 'draft'),
                'intent' => returnAttribute($resContent, ['intent'], 'proposal'),
                'priority' => returnAttribute($resContent, ['priority']),
                'do_not_perform' => returnAttribute($resContent, ['doNotPerform']),
                'quantity' => returnVariableAttribute($resContent, 'quantity', ['Quantity', 'Ratio', 'Range']),
                'subject' => returnAttribute($resContent, ['subject', 'reference'], ''),
                'encounter' => returnAttribute($resContent, ['encounter', 'reference'], ''),
                'occurrence' => returnVariableAttribute($resContent, 'occurrence', ['DateTime', 'Period', 'Timing']),
                'as_needed' => returnVariableAttribute($resContent, 'asNeeded', ['Boolean', 'CodeableConcept']),
                'authored_on' => parseDateInput(returnAttribute($resContent, ['authoredOn'])),
                'requester' => returnAttribute($resContent, ['requester', 'reference']),
                'patient_instruction' => returnAttribute($resContent, ['patientInstruction'])
            ];

            $serviceReq = ServiceRequest::create(merge_array(
                $requisition,
                $arr,
                $code
            ));

            $fk = ['request_id' => $serviceReq->id];

            parseAndCreate(ServiceRequestIdentifier::class, returnAttribute($resContent, ['identifier']), 'returnIdentifier', $fk);
            parseAndCreate(ServiceRequestBasedOn::class, returnAttribute($resContent, ['basedOn']), 'returnReference', $fk);
            parseAndCreate(ServiceRequestReplaces::class, returnAttribute($resContent, ['replaces']), 'returnReference', $fk);
            parseAndCreate(ServiceRequestCategory::class, returnAttribute($resContent, ['category']), 'returnCodeableConcept', $fk);
            parseAndCreate(ServiceRequestOrderDetail::class, returnAttribute($resContent, ['orderDetail']), 'returnCodeableConcept', $fk);
            parseAndCreate(ServiceRequestPerformer::class, returnAttribute($resContent, ['performer']), 'returnReference', $fk);
            parseAndCreateCompound(ServiceRequestLocation::class, $resContent, ['locationCode' => 'returnCodeableConcept', 'locationReference' => 'returnReference'], $fk);
            parseAndCreateCompound(ServiceRequestReason::class, $resContent, ['reasonCode' => 'returnCodeableConcept', 'reasonReference' => 'returnReference'], $fk);
            parseAndCreate(ServiceRequestInsurance::class, returnAttribute($resContent, ['insurance']), 'returnReference', $fk);
            parseAndCreate(ServiceRequestSupportingInfo::class, returnAttribute($resContent, ['supportingInfo']), 'returnReference', $fk);
            parseAndCreate(ServiceRequestSpecimen::class, returnAttribute($resContent, ['specimen']), 'returnReference', $fk);
            parseAndCreate(ServiceRequestBodySite::class, returnAttribute($resContent, ['bodySite']), 'returnCodeableConcept', $fk);
            parseAndCreate(ServiceRequestNote::class, returnAttribute($resContent, ['note']), 'returnAnnotation', $fk);
            parseAndCreate(ServiceRequestRelevantHistory::class, returnAttribute($resContent, ['relevantHistory']), 'returnReference', $fk);
        }
    }
}
