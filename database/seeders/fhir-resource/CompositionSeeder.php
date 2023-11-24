<?php

namespace Database\Seeders;

use App\Models\Composition;
use App\Models\CompositionAttester;
use App\Models\CompositionAuthor;
use App\Models\CompositionCategory;
use App\Models\CompositionEvent;
use App\Models\CompositionEventCode;
use App\Models\CompositionEventDetail;
use App\Models\CompositionRelatesTo;
use App\Models\CompositionSection;
use App\Models\CompositionSectionAuthor;
use App\Models\CompositionSectionEntry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\ResourceContent;

class CompositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $compositions = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'Composition')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($compositions as $c) {
            $resContent = json_decode($c->res_text, true);
            $identifier = returnAttribute($resContent, ['identifier']);
            $identifierData = returnIdentifier($identifier, 'identifier');
            $type = returnAttribute($resContent, ['type']);
            $typeData = returnCodeableConcept($type, 'type');
            $event = returnAttribute($resContent, ['event']);
            $section = returnAttribute($resContent, ['section']);

            $comp = Composition::create(merge_array(
                [
                    'resource_id' => $c->id,
                    'status' => returnAttribute($resContent, ['status']),
                    'subject' => returnAttribute($resContent, ['subject', 'reference']),
                    'encounter' => returnAttribute($resContent, ['encounter', 'reference']),
                    'date' => returnAttribute($resContent, ['date']),
                    'title' => returnAttribute($resContent, ['title']),
                    'confidentiality' => returnAttribute($resContent, ['confidentiality']),
                    'custodian' => returnAttribute($resContent, ['custodian', 'reference'])
                ],
                $identifierData,
                $typeData,
            ));

            $foreignKey = ['composition_id' => $comp->id];

            parseAndCreate(CompositionCategory::class, returnAttribute($resContent, ['category']), 'returnCodeableConcept', $foreignKey);
            parseAndCreate(CompositionAuthor::class, returnAttribute($resContent, ['author']), 'returnReference', $foreignKey);
            parseAndCreate(CompositionAttester::class, returnAttribute($resContent, ['attester']), 'returnAttester', $foreignKey);
            parseAndCreate(CompositionRelatesTo::class, returnAttribute($resContent, ['relatesTo']), 'returnRelatesTo', $foreignKey);

            if (is_array($event) || is_object($event)) {
                foreach ($event as $e) {
                    $period = returnPeriod(returnAttribute($e, ['period']));

                    $ce = CompositionEvent::create(merge_array($period, $foreignKey));

                    $ceFk = ['composition_event_id' => $ce->id];

                    parseAndCreate(CompositionEventCode::class, returnAttribute($e, ['code']), 'returnCodeableConcept', $ceFk);
                    parseAndCreate(CompositionEventDetail::class, returnAttribute($e, ['detail']), 'returnReference', $ceFk);
                }
            }

            if (is_array($section) || is_object($section)) {
                foreach ($section as $s) {
                    $text = returnNarrative(returnAttribute($s, ['text']), 'text');

                    $cs = CompositionSection::create(merge_array(
                        $foreignKey,
                        [
                            'title' => returnAttribute($s, ['title']),
                            'code' => returnAttribute($s, ['code', 'coding', 0, 'code']),
                            'focus' => returnAttribute($s, ['focus', 'reference']),
                            'mode' => returnAttribute($s, ['mode']),
                            'ordered_by' => returnAttribute($s, ['orderedBy', 'coding', 0, 'code']),
                            'empty_reason' => returnAttribute($s, ['emptyReason', 'coding', 0, 'code'])
                        ],
                        $text,
                    ));

                    $csFk = ['composition_section_id' => $cs->id];

                    parseAndCreate(CompositionSectionAuthor::class, returnAttribute($s, ['author']), 'returnReference', $csFk);
                    parseAndCreate(CompositionSectionEntry::class, returnAttribute($s, ['entry']), 'returnReference', $csFk);
                }
            }
        }
    }
}
