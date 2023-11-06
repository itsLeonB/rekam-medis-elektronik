<?php

namespace Database\Seeders;

use App\Models\Observation;
use App\Models\ObservationBasedOn;
use App\Models\ObservationCategory;
use App\Models\ObservationComponent;
use App\Models\ObservationComponentInterpretation;
use App\Models\ObservationComponentReferenceRange;
use App\Models\ObservationDerivedFrom;
use App\Models\ObservationFocus;
use App\Models\ObservationIdentifier;
use App\Models\ObservationInterpretation;
use App\Models\ObservationMember;
use App\Models\ObservationNote;
use App\Models\ObservationPartOf;
use App\Models\ObservationPerformer;
use App\Models\ObservationReferenceRange;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class ObservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $observations = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'Observation')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($observations as $o) {
            $resContent = json_decode($o->res_text, true);
            $identifiers = returnAttribute($resContent, ['identifier'], null);
            $basedOn = returnAttribute($resContent, ['basedOn'], null);
            $partOf = returnAttribute($resContent, ['partOf'], null);
            $category = returnAttribute($resContent, ['category'], null);
            $focus = returnAttribute($resContent, ['focus'], null);
            $performer = returnAttribute($resContent, ['performer'], null);
            $interpretation = returnAttribute($resContent, ['interpretation'], null);
            $notes = returnAttribute($resContent, ['note'], null);
            $referenceRange = returnAttribute($resContent, ['referenceRange'], null);
            $members = returnAttribute($resContent, ['hasMember'], null);
            $derivedFrom = returnAttribute($resContent, ['derivedFrom'], null);
            $component = returnAttribute($resContent, ['component'], null);

            $obs = Observation::create(
                [
                    'resource_id' => $o->id,
                    'status' => returnAttribute($resContent, ['status'], 'unknown'),
                    'code_system' => returnAttribute($resContent, ['code', 'coding', 0, 'system']),
                    'code_code' => returnAttribute($resContent, ['code', 'coding', 0, 'code'], ''),
                    'code_display' => returnAttribute($resContent, ['code', 'coding', 0, 'display']),
                    'subject' => returnAttribute($resContent, ['subject', 'reference'], ''),
                    'encounter' => returnAttribute($resContent, ['encounter', 'reference'], ''),
                    'effective' => returnVariableAttribute($resContent, 'effective', ['DateTime', 'Period', 'Timing', 'Instant']),
                    'issued' => returnAttribute($resContent, ['issued']),
                    'value' => returnValue($resContent),
                    'data_absent_reason' => returnAttribute($resContent, ['dataAbsentReason', 'coding', 0, 'code']),
                    'body_site_system' => returnAttribute($resContent, ['bodySite', 'coding', 0, 'system']),
                    'body_site_code' => returnAttribute($resContent, ['bodySite', 'coding', 0, 'code']),
                    'body_site_display' => returnAttribute($resContent, ['bodySite', 'coding', 0, 'display']),
                    'method_system' => returnAttribute($resContent, ['method', 'coding', 0, 'system']),
                    'method_code' => returnAttribute($resContent, ['method', 'coding', 0, 'code']),
                    'method_display' => returnAttribute($resContent, ['method', 'coding', 0, 'display']),
                    'specimen' => returnAttribute($resContent, ['specimen', 'reference']),
                    'device' => returnAttribute($resContent, ['device', 'reference']),
                ]
            );

            $foreignKey = ['observation_id' => $obs->id];
            parseAndCreate(ObservationIdentifier::class, $identifiers, 'returnIdentifier', $foreignKey);
            parseAndCreate(ObservationBasedOn::class, $basedOn, 'returnReference', $foreignKey);
            parseAndCreate(ObservationPartOf::class, $partOf, 'returnReference', $foreignKey);
            parseAndCreate(ObservationCategory::class, $category, 'getCategoryDetails', $foreignKey);
            parseAndCreate(ObservationFocus::class, $focus, 'returnReference', $foreignKey);
            parseAndCreate(ObservationPerformer::class, $performer, 'returnReference', $foreignKey);
            parseAndCreate(ObservationInterpretation::class, $interpretation, 'returnCodeableConcept', $foreignKey);
            parseAndCreate(ObservationNote::class, $notes, 'returnAnnotation', $foreignKey);
            parseAndCreate(ObservationReferenceRange::class, $referenceRange, 'returnReferenceRange', $foreignKey);
            parseAndCreate(ObservationMember::class, $members, 'returnReference', $foreignKey);
            parseAndCreate(ObservationDerivedFrom::class, $derivedFrom, 'returnReference', $foreignKey);

            if (is_array($component) || is_object($component)) {
                foreach ($component as $c) {
                    $componentData = returnComponent($c);
                    $componentInterpretation = returnAttribute($c, ['interpretation'], null);
                    $componentReferenceRange = returnAttribute($c, ['referenceRange'], null);
                    $comp = ObservationComponent::create(array_merge($componentData, $foreignKey));
                    $componentFk = ['obs_comp_id' => $comp->id];
                    parseAndCreate(ObservationComponentInterpretation::class, $componentInterpretation, 'returnCodeableConcept', $componentFk);
                    parseAndCreate(ObservationComponentReferenceRange::class, $componentReferenceRange, 'returnReferenceRange', $componentFk);
                }
            }
        }
    }
}
