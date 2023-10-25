<?php

namespace Database\Seeders;

use App\Models\AllergyIntolerance;
use App\Models\AllergyIntoleranceCategory;
use App\Models\AllergyIntoleranceIdentifier;
use App\Models\AllergyIntoleranceNote;
use App\Models\AllergyIntoleranceReaction;
use App\Models\AllergyIntoleranceReactionManifestation;
use App\Models\AllergyIntoleranceReactionNote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class AllergyIntoleranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergies = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'AllergyIntolerance')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($allergies as $a) {
            $resContent = json_decode($a->res_text, true);
            $categories = returnAttribute($resContent, ['category'], []);
            $category = [
                'category_food' => false,
                'category_medication' => false,
                'category_environment' => false,
                'category_biologic' => false
            ];
            foreach ($categories as $cat) {
                $category['category_' . $cat] = true;
            }
            $code = returnCodeableConcept(returnAttribute($resContent, ['code']), 'code');

            $ai = AllergyIntolerance::create(merge_array(
                [
                    'resource_id' => $a->id,
                    'clinical_status' => returnAttribute($resContent, ['clinicalStatus', 'coding', 0, 'code']),
                    'verification_status' => returnAttribute($resContent, ['verificationStatus', 'coding', 0, 'code']),
                    'type' => returnAttribute($resContent, ['type']),
                    'criticality' => returnAttribute($resContent, ['criticality']),
                    'patient' => returnAttribute($resContent, ['patient', 'reference'], ''),
                    'encounter' => returnAttribute($resContent, ['encounter', 'reference']),
                    'onset' => returnVariableAttribute($resContent, 'onset', ['DateTime', 'Age', 'Period', 'Range', 'String']),
                    'recorded_date' => parseDate(returnAttribute($resContent, ['recordedDate'])),
                    'recorder' => returnAttribute($resContent, ['recorder', 'reference']),
                    'asserter' => returnAttribute($resContent, ['asserter', 'reference']),
                    'last_occurence' => parseDate(returnAttribute($resContent, ['lastOccurence']))
                ],
                $code,
                $category
            ));

            $foreignKey = ['allergy_id' => $ai->id];

            parseAndCreate(AllergyIntoleranceIdentifier::class, returnAttribute($resContent, ['identifier']), 'returnIdentifier', $foreignKey);
            parseAndCreate(AllergyIntoleranceNote::class, returnAttribute($resContent, ['note']), 'returnAnnotation', $foreignKey);

            $reactions = returnAttribute($resContent, ['reaction']);
            if (is_array($reactions) || is_object($reactions)) {
                foreach ($reactions as $r) {
                    $air = AllergyIntoleranceReaction::create(merge_array(
                        returnCodeableConcept(returnAttribute($r, ['substance']), 'substance'),
                        [
                            'description' => returnAttribute($r, ['description']),
                            'onset' => parseDate(returnAttribute($r, ['onset'])),
                            'severity' => returnAttribute($r, ['severity'])
                        ],
                        returnCodeableConcept(returnAttribute($r, ['exposureRoute']), 'exposure_route')
                    ));

                    $airFk = ['allergy_react_id' => $air->id];

                    parseAndCreate(AllergyIntoleranceReactionManifestation::class, returnAttribute($r, ['manifestation']), 'returnCodeableConcept', $airFk);
                    parseAndCreate(AllergyIntoleranceReactionNote::class, returnAttribute($r, ['note']), 'returnAnnotation', $airFk);
                }
            }
        }
    }
}
