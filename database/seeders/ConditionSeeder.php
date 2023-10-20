<?php

namespace Database\Seeders;

use App\Models\Condition;
use App\Models\ConditionBodySite;
use App\Models\ConditionCategory;
use App\Models\ConditionEvidence;
use App\Models\ConditionIdentifier;
use App\Models\ConditionNote;
use App\Models\ConditionStage;
use App\Models\ConditionStageAssessment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'Condition')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($conditions as $c) {
            $resContent = json_decode($c->res_text, true);
            $identifiers = returnAttribute($resContent, ['identifier'], null);
            $category = getCategory($resContent);
            $bodySite = getBodySite($resContent);
            $stage = getStage($resContent);
            $evidence = returnAttribute($resContent, ['evidence'], null);
            $note = returnAttribute($resContent, ['note'], null);
            $severity = returnAttribute($resContent, ['severity', 'coding', 0, 'code'], null);
            $severitySet = ['24484000', '6736007', '255604002'];
            if ($severity === null) {
                $severity = null;
            } else {
                if (in_array($severity, $severitySet)) {
                    // Value found in the list, do nothing
                } else {
                    // Value not found in the list, assign '6736007'
                    $severity = '6736007';
                }
            }

            $cond = Condition::create(
                [
                    'resource_id' => $c->id,
                    'clinical_status' => returnAttribute($resContent, ['clinicalStatus', 'coding', 0, 'code'], null),
                    'verification_status' => returnAttribute($resContent, ['verificationStatus', 'coding', 0, 'code'], null),
                    'severity' =>  $severity,
                    'code' => isset($resContent['code']['coding'][0]['code']) && !empty($resContent['code']['coding'][0]['code']) ? $resContent['code']['coding'][0]['code'] : '',
                    'subject' => isset($resContent['subject']['reference']) && !empty($resContent['subject']['reference']) ? $resContent['subject']['reference'] : '',
                    'encounter' => isset($resContent['encounter']['reference']) && !empty($resContent['encounter']['reference']) ? $resContent['encounter']['reference'] : '',
                    'onset' => returnVariableAttribute($resContent, 'onset', ['DateTime', 'Age', 'Period', 'Range', 'String']),
                    'abatement' => returnVariableAttribute($resContent, 'abatement', ['DateTime', 'Age', 'Period', 'Range', 'String']),
                    'recorded_date' => isset($resContent['recordedDate']) && !empty($resContent['recordedDate']) ? $resContent['recordedDate'] : '1900-01-01',
                    'recorder' => isset($resContent['recorder']['reference']) && !empty($resContent['recorder']['reference']) ? $resContent['recorder']['reference'] : '',
                    'asserter' => isset($resContent['asserter']['reference']) && !empty($resContent['asserter']['reference']) ? $resContent['asserter']['reference'] : ''
                ]
            );

            $foreignKey = ['condition_id' => $cond->id];

            parseAndCreate(ConditionIdentifier::class, $identifiers, 'returnIdentifier', $foreignKey);

            if (is_array($category) || is_object($category)) {
                foreach ($category as $cat) {
                    $categoryDetails = getCategoryDetails($cat);
                    ConditionCategory::create(
                        [
                            'condition_id' => $cond->id,
                            'system' => $categoryDetails['system'],
                            'code' => $categoryDetails['code'],
                            'display' => $categoryDetails['display']
                        ]
                    );
                }
            }

            if (is_array($bodySite) || is_object($bodySite)) {
                foreach ($bodySite as $bs) {
                    $bodySiteDetails = getBodySiteDetails($bs);
                    ConditionBodySite::create(
                        [
                            'condition_id' => $cond->id,
                            'system' => $bodySiteDetails['system'],
                            'code' => $bodySiteDetails['code'],
                            'display' => $bodySiteDetails['display']
                        ]
                    );
                }
            }

            if (is_array($stage) || is_object($stage)) {
                foreach ($stage as $s) {
                    $stageDetails = getStageDetails($s);
                    $assessment = getAssessment($s);

                    $cs = ConditionStage::create(
                        [
                            'condition_id' => $cond->id,
                            'summary_system' => $stageDetails['summarySystem'],
                            'summary_code' => $stageDetails['summaryCode'],
                            'summary_display' => $stageDetails['summaryDisplay'],
                            'type_system' => $stageDetails['typeSystem'],
                            'type_code' => $stageDetails['typeCode'],
                            'type_display' => $stageDetails['typeDisplay'],
                        ]
                    );

                    if (is_array($assessment) || is_object($assessment)) {
                        foreach ($assessment as $a) {
                            if (isset($a['reference']) && !empty($a['reference'])) {
                                ConditionStageAssessment::create(
                                    [
                                        'condition_stage_id' => $cs->id,
                                        'reference' => $a['reference']
                                    ]
                                );
                            }
                        }
                    }
                }
            }

            parseAndCreate(ConditionEvidence::class, $evidence, 'returnEvidence', $foreignKey);
            parseAndCreate(ConditionNote::class, $note, 'returnAnnotation', $foreignKey);
        }
    }
}
