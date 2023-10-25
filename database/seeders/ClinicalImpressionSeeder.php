<?php

namespace Database\Seeders;

use App\Models\ClinicalImpression;
use App\Models\ClinicalImpressionFinding;
use App\Models\ClinicalImpressionIdentifier;
use App\Models\ClinicalImpressionInvestigation;
use App\Models\ClinicalImpressionInvestigationItem;
use App\Models\ClinicalImpressionNote;
use App\Models\ClinicalImpressionProblem;
use App\Models\ClinicalImpressionPrognosis;
use App\Models\ClinicalImpressionProtocol;
use App\Models\ClinicalImpressionSupportingInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class ClinicalImpressionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinicalImpressions = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'ClinicalImpression')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($clinicalImpressions as $ci) {
            $resContent = json_decode($ci->res_text, true);
            $statusReason = returnCodeableConcept(returnAttribute($resContent, ['statusReason']), 'status_reason');
            $code = returnCodeableConcept(returnAttribute($resContent, ['code']), 'code');

            $impress = ClinicalImpression::create(merge_array(
                [
                    'resource_id' => $ci->id,
                    'status' => returnAttribute($resContent, ['status'], 'in-progress'),
                    'description' => returnAttribute($resContent, ['description']),
                    'subject' => returnAttribute($resContent, ['subject', 'reference'], ''),
                    'encounter' => returnAttribute($resContent, ['encounter', 'reference'], ''),
                    'effective' => returnVariableAttribute($resContent, 'effective', ['DateTime', 'Period']),
                    'date' => parseDate(returnAttribute($resContent, ['date'])),
                    'assessor' => returnAttribute($resContent, ['assessor', 'reference']),
                    'previous' => returnAttribute($resContent, ['previous', 'reference']),
                    'summary' => returnAttribute($resContent, ['summary']),
                ],
                $statusReason,
                $code
            ));

            $foreignKey = ['impression_id' => $impress->id];

            parseAndCreate(ClinicalImpressionIdentifier::class, returnAttribute($resContent, ['identifier']), 'returnIdentifier', $foreignKey);
            parseAndCreate(ClinicalImpressionProblem::class, returnAttribute($resContent, ['problem']), 'returnReference', $foreignKey);

            $investigations = returnAttribute($resContent, ['investigation']);

            if (is_array($investigations) || is_object($investigations)) {
                foreach ($investigations as $i) {
                    $text = ['text' => returnAttribute($i, ['code', 'text'])];
                    $cii = ClinicalImpressionInvestigation::create(merge_array($foreignKey, returnCodeableConcept($i), $text));
                    $ciiFk = ['impress_investigate_id' => $cii->id];
                    parseAndCreate(ClinicalImpressionInvestigationItem::class, returnAttribute($i, ['item']), 'returnReference', $ciiFk);
                }
            }

            $protocols = returnAttribute($resContent, ['protocol']);

            if (is_array($protocols) || is_object($protocols)) {
                foreach ($protocols as $p) {
                    ClinicalImpressionProtocol::create(
                        [
                            'impression_id' => $impress->id,
                            'uri' => $p
                        ]
                    );
                }
            }

            parseAndCreate(ClinicalImpressionFinding::class, returnAttribute($resContent, ['finding']), 'returnFinding', $foreignKey);
            parseAndCreateCompound(ClinicalImpressionPrognosis::class, $resContent, ['prognosisCodeableConcept' => 'returnCodeableConcept', 'prognosisReference' => 'returnReference'], $foreignKey);
            parseAndCreate(ClinicalImpressionSupportingInfo::class, returnAttribute($resContent, ['supportingInfo']), 'returnReference', $foreignKey);
            parseAndCreate(ClinicalImpressionNote::class, returnAttribute($resContent, ['note']), 'returnAnnotation', $foreignKey);
        }
    }
}
