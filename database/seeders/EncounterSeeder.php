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
            $identifiers = returnAttribute($resContent, ['identifier'], null);
            $statusHistory = returnAttribute($resContent, ['statusHistory'], null);
            $classHistory = getClassHistory($resContent);
            $participants = returnAttribute($resContent, ['participant'], null);
            $diagnosis = returnAttribute($resContent, ['diagnosis'], null);
            $period = getPeriod($resContent);

            $enc = Encounter::create(
                [
                    'resource_id' => $e->id,
                    'status' => returnAttribute($resContent, ['status'], 'unknown') === 'completed' ? 'finished' : returnAttribute($resContent, ['status'], 'unknown'),
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

            $foreignKey = ['encounter_id' => $enc->id];

            parseAndCreate(EncounterIdentifier::class, $identifiers, 'returnIdentifier', $foreignKey);
            parseAndCreate(EncounterStatusHistory::class, $statusHistory, 'returnStatusHistory', $foreignKey);

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

            parseAndCreate(EncounterParticipant::class, $participants, 'returnParticipant', $foreignKey);
            parseAndCreate(EncounterDiagnosis::class, $diagnosis, 'returnDiagnosis', $foreignKey);
        }
    }
}
