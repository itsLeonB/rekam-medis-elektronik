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
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Condition');
        })->get();

        foreach ($conditions as $c) {
            $resContent = json_decode($c->res_text, true);
            $identifiers = getIdentifier($resContent);
            $category = getCategory($resContent);
            $bodySite = getBodySite($resContent);
            $stage = getStage($resContent);
            $evidence = getEvidence($resContent);
            $note = getNote($resContent);

            $cond = Condition::create(
                [
                    'resource_id' => $c->id,
                    'clinical_status' => isset($resContent['clinicalStatus']['coding'][0]['code']) && !empty($resContent['clinicalStatus']['coding'][0]['code']) ? $resContent['clinicalStatus']['coding'][0]['code'] : '',
                    'verification_status' => isset($resContent['verificationStatus']['coding'][0]['code']) && !empty($resContent['verificationStatus']['coding'][0]['code']) ? $resContent['verificationStatus']['coding'][0]['code'] : '',
                    'severity' => isset($resContent['severity']['coding'][0]['code']) && !empty($resContent['severity']['coding'][0]['code']) ? $resContent['severity']['coding'][0]['code'] : 0,
                    'code' => isset($resContent['code']['coding'][0]['code']) && !empty($resContent['code']['coding'][0]['code']) ? $resContent['code']['coding'][0]['code'] : '',
                    'subject' => isset($resContent['subject']['reference']) && !empty($resContent['subject']['reference']) ? $resContent['subject']['reference'] : '',
                    'encounter' => isset($resContent['encounter']['reference']) && !empty($resContent['encounter']['reference']) ? $resContent['encounter']['reference'] : '',
                    'onset_datetime' => isset($resContent['onsetDateTime']) && !empty($resContent['onsetDateTime']) ? $resContent['onsetDateTime'] : '1900-01-01',
                    'onset_age' => isset($resContent['onsetAge']['value']) && !empty($resContent['onsetAge']['value']) ? $resContent['onsetAge']['value'] : 0,
                    'onset_string' => isset($resContent['onsetString']) && !empty($resContent['onsetString']) ? $resContent['onsetString'] : '',
                    'abatement_datetime' => isset($resContent['abatementDateTime']) && !empty($resContent['abatementDateTime']) ? $resContent['abatementDateTime'] : '1900-01-01',
                    'abatement_age' => isset($resContent['abatementAge']['value']) && !empty($resContent['abatementAge']['value']) ? $resContent['abatementAge']['value'] : 0,
                    'abatement_string' => isset($resContent['abatementString']) && !empty($resContent['abatementString']) ? $resContent['abatementString'] : '',
                    'recorded_date' => isset($resContent['recordedDate']) && !empty($resContent['recordedDate']) ? $resContent['recordedDate'] : '1900-01-01',
                    'recorder' => isset($resContent['recorder']['reference']) && !empty($resContent['recorder']['reference']) ? $resContent['recorder']['reference'] : '',
                    'asserter' => isset($resContent['asserter']['reference']) && !empty($resContent['asserter']['reference']) ? $resContent['asserter']['reference'] : ''
                ]
            );

            if (is_array($identifiers) || is_object($identifiers)) {
                foreach ($identifiers as $i) {
                    $identifierDetails = parseIdentifier($i);
                    ConditionIdentifier::create(
                        [
                            'condition_id' => $cond->id,
                            'system' => $identifierDetails['system'],
                            'use' => $identifierDetails['use'],
                            'value' => $identifierDetails['value']
                        ]
                    );
                }
            }

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

            if (is_array($evidence) || is_object($evidence)) {
                foreach ($evidence as $e) {
                    $evidenceDetails = getEvidenceDetails($e);
                    ConditionEvidence::create(
                        [
                            'condition_id' => $cond->id,
                            'code' => $evidenceDetails['code'],
                            'detail_reference' => $evidenceDetails['detailReference']
                        ]
                    );
                }
            }

            if (is_array($note) || is_object($note)) {
                foreach ($note as $n) {
                    $noteDetails = getNoteDetails($n);
                    ConditionNote::create(
                        [
                            'condition_id' => $cond->id,
                            'author' => $noteDetails['author'],
                            'time' => $noteDetails['time'],
                            'text' => $noteDetails['text']
                        ]
                    );
                }
            }
        }
    }
}
