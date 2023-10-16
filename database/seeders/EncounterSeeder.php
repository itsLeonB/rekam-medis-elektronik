<?php

namespace Database\Seeders;

use App\Models\Encounter;
use App\Models\EncounterDiagnosis;
use App\Models\EncounterIdentifier;
use App\Models\EncounterParticipant;
use App\Models\EncounterStatusHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class EncounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encounters = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Encounter');
        })->get();

        foreach ($encounters as $e) {
            $resContent = json_decode($e->res_text, true);
            $identifiers = getIdentifier($resContent);
            $statusHistory = getStatusHistory($resContent);
            $classHistory = getClassHistory($resContent);
            $participants = getParticipants($resContent);
            $diagnosis = getDiagnosis($resContent);
            $period = getPeriod($resContent);

            $enc = Encounter::create(
                [
                    'resource_id' => $e->id,
                    'status' => $resContent['status'],
                    'class' => getClass($resContent),
                    'service_type' => getServiceType($resContent),
                    'priority' => getPriority($resContent),
                    'subject' => $resContent['subject']['reference'],
                    'episode_of_care' => getEpisodeOfCare($resContent),
                    'based_on' => getBasedOn($resContent),
                    'period_start' => $period['start'],
                    'period_end' => $period['end'],
                    'account' => getAccount($resContent),
                    'location' => getLocation($resContent),
                    'service_provider' => getServiceProvider($resContent),
                    'part_of' => getPartOf($resContent)
                ]
            );

            if (is_array($identifiers) || is_object($identifiers)) {
                foreach ($identifiers as $i) {
                    $identifierDetails = parseIdentifier($i);
                    EncounterIdentifier::create(
                        [
                            'encounter_id' => $enc->id,
                            'system' => $identifierDetails['system'],
                            'use' => $identifierDetails['use'],
                            'value' => $identifierDetails['value']
                        ]
                    );
                }
            }

            if (is_array($statusHistory) || is_object($statusHistory)) {
                foreach ($statusHistory as $sh) {
                    EncounterStatusHistory::create(
                        [
                            'encounter_id' => $enc->id,
                            'status' => $sh['status'],
                            'period_start' => $sh['period']['start'],
                            'period_end' => $sh['period']['end']
                        ]
                    );
                }
            }

            if (is_array($classHistory) || is_object($classHistory)) {
                foreach ($classHistory as $ch) {
                    EncounterStatusHistory::create(
                        [
                            'encounter_id' => $enc->id,
                            'class' => $ch['class'],
                            'period_start' => $ch['period']['start'],
                            'period_end' => $ch['period']['end']
                        ]
                    );
                }
            }

            if (is_array($participants) || is_object($participants)) {
                foreach ($participants as $p) {
                    EncounterParticipant::create(
                        [
                            'encounter_id' => $enc->id,
                            'type' => getParticipantType($p),
                            'individual' => getIndividual($p)
                        ]
                    );
                }
            }

            if (is_array($diagnosis) || is_object($diagnosis)) {
                foreach ($diagnosis as $d) {
                    $diagnosisDetails = getDiagnosisDetails($d);

                    EncounterDiagnosis::create(
                        [
                            'encounter_id' => $enc->id,
                            'condition_reference' => $diagnosisDetails['conditionReference'],
                            'condition_display' => $diagnosisDetails['conditionDisplay'],
                            'use' => $diagnosisDetails['use'],
                            'rank' => $diagnosisDetails['rank']
                        ]
                    );
                }
            }
        }
    }
}
