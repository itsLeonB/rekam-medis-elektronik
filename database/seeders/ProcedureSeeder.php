<?php

namespace Database\Seeders;

use App\Models\Procedure;
use App\Models\ProcedureBasedOn;
use App\Models\ProcedureBodySite;
use App\Models\ProcedureComplication;
use App\Models\ProcedureFocalDevice;
use App\Models\ProcedureFollowUp;
use App\Models\ProcedureIdentifier;
use App\Models\ProcedureItemUsed;
use App\Models\ProcedureNote;
use App\Models\ProcedurePartOf;
use App\Models\ProcedurePerformer;
use App\Models\ProcedureReason;
use App\Models\ProcedureReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $procedures = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'Procedure')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($procedures as $p) {
            $resContent = json_decode($p->res_text, true);
            $identifiers = returnAttribute($resContent, ['identifier'], null);
            $basedOn = returnAttribute($resContent, ['basedOn'], null);
            $partOf = returnAttribute($resContent, ['partOf'], null);
            $performer = returnAttribute($resContent, ['performer'], null);
            $bodySite = returnAttribute($resContent, ['bodySite'], null);
            $report = returnAttribute($resContent, ['report'], null);
            $followUp = returnAttribute($resContent, ['followUp'], null);
            $note = returnAttribute($resContent, ['note'], null);
            $focalDevice = returnAttribute($resContent, ['focalDevice'], null);

            $proc = Procedure::create(
                [
                    'resource_id' => $p->id,
                    'status' => returnAttribute($resContent, ['status'], 'unknown'),
                    'status_reason' => returnAttribute($resContent, ['statusReason', 'coding', 0, 'code'], null),
                    'category' => returnAttribute($resContent, ['category', 'coding', 0, 'code'], null),
                    'code' => returnAttribute($resContent, ['code', 'coding', 0, 'code'], ''),
                    'subject' => returnAttribute($resContent, ['subject', 'reference'], ''),
                    'encounter' => returnAttribute($resContent, ['encounter', 'reference'], ''),
                    'performed' => returnVariableAttribute($resContent, 'performed', ['DateTime', 'Period', 'String', 'Age', 'Range']),
                    'recorder' => returnAttribute($resContent, ['recorder', 'reference'], null),
                    'asserter' => returnAttribute($resContent, ['asserter', 'reference'], null),
                    'location' => returnAttribute($resContent, ['location', 'reference'], null),
                    'outcome' => returnAttribute($resContent, ['outcome', 'coding', 0, 'code'], null)
                ]
            );

            $foreignKey = ['procedure_id' => $proc->id];

            parseAndCreate(ProcedureIdentifier::class, $identifiers, 'returnIdentifier', $foreignKey);
            parseAndCreate(ProcedureBasedOn::class, $basedOn, 'returnReference', $foreignKey);
            parseAndCreate(ProcedurePartOf::class, $partOf, 'returnReference', $foreignKey);
            parseAndCreate(ProcedurePerformer::class, $performer, 'returnProcedurePerformer', $foreignKey);
            parseAndCreateCompound(ProcedureReason::class, $resContent, ['reasonCode' => 'returnCodeableConcept', 'reasonReference' => 'returnReference'], $foreignKey);
            parseAndCreate(ProcedureBodySite::class, $bodySite, 'returnCodeableConcept', $foreignKey);
            parseAndCreate(ProcedureReport::class, $report, 'returnReference', $foreignKey);
            parseAndCreateCompound(ProcedureComplication::class, $resContent, ['complication' => 'returnCodeableConcept', 'complicationDetail' => 'returnReference'], $foreignKey);
            parseAndCreate(ProcedureFollowUp::class, $followUp, 'returnCodeableConcept', $foreignKey);
            parseAndCreate(ProcedureNote::class, $note, 'returnAnnotation', $foreignKey);
            parseAndCreate(ProcedureFocalDevice::class, $focalDevice, 'returnFocalDevice', $foreignKey);
            parseAndCreateCompound(ProcedureItemUsed::class, $resContent, ['usedReference' => 'returnReference', 'usedCode' => 'returnCodeableConcept'], $foreignKey);
        }
    }
}
