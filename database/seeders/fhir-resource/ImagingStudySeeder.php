<?php

namespace Database\Seeders;

use App\Models\ImagingStudy;
use App\Models\ImagingStudyBasedOn;
use App\Models\ImagingStudyIdentifier;
use App\Models\ImagingStudyInterpreter;
use App\Models\ImagingStudyModality;
use App\Models\ImagingStudyNote;
use App\Models\ImagingStudyProcedure;
use App\Models\ImagingStudyReason;
use App\Models\ImagingStudySeries;
use App\Models\ImagingStudySeriesPerformer;
use App\Models\ImagingStudySeriesSpecimen;
use App\Models\ImagingStudySeriesInstance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class ImagingStudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imagings = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'ImagingStudy')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($imagings as $i) {
            $resContent = json_decode($i->res_text, true);
            $identifiers = returnAttribute($resContent, ['identifier'], null);
            $modality = returnAttribute($resContent, ['modality'], null);
            $basedOn = returnAttribute($resContent, ['basedOn'], null);
            $interpreter = returnAttribute($resContent, ['interpreter'], null);
            $procedure = returnAttribute($resContent, ['procedureCode'], null);
            $reason = returnAttribute($resContent, ['reasonCode'], null);
            $note = returnAttribute($resContent, ['note'], null);
            $series = returnAttribute($resContent, ['series'], null);

            $img = ImagingStudy::create(
                [
                    'resource_id' => $i->id,
                    'status' => returnAttribute($resContent, ['status'], 'unknown'),
                    'subject' => returnAttribute($resContent, ['subject', 'reference'], ''),
                    'encounter' => returnAttribute($resContent, ['encounter', 'reference'], null),
                    'started' => returnAttribute($resContent, ['started'], null),
                    'referrer' => returnAttribute($resContent, ['referrer', 'reference'], null),
                    'series_num' => returnAttribute($resContent, ['numberOfSeries'], null),
                    'instances_num' => returnAttribute($resContent, ['numberOfInstances'], null),
                    'procedure_reference' => returnAttribute($resContent, ['procedureReference', 'reference'], null),
                    'location' => returnAttribute($resContent, ['location', 'reference'], null),
                    'description' => returnAttribute($resContent, ['description'], null)
                ]
            );

            $foreignKey = ['imaging_id' => $img->id];

            parseAndCreate(ImagingStudyIdentifier::class, $identifiers, 'returnIdentifier', $foreignKey);
            parseAndCreate(ImagingStudyModality::class, $modality, 'returnCoding', $foreignKey);
            parseAndCreate(ImagingStudyBasedOn::class, $basedOn, 'returnReference', $foreignKey);
            parseAndCreate(ImagingStudyInterpreter::class, $interpreter, 'returnReference', $foreignKey);
            parseAndCreate(ImagingStudyProcedure::class, $procedure, 'returnCodeableConcept', $foreignKey);
            parseAndCreate(ImagingStudyReason::class, $reason, 'returnCodeableConcept', $foreignKey);
            parseAndCreate(ImagingStudyNote::class, $note, 'returnAnnotation', $foreignKey);

            foreach ($series as $s) {
                $seriesData = returnSeries($s);
                $seriesEntry = ImagingStudySeries::create(array_merge($seriesData, $foreignKey));
                $seriesFk = ['img_series_id' => $seriesEntry->id];
                $specimen = returnAttribute($s, ['specimen'], null);
                $performer = returnAttribute($s, ['performer'], null);
                $instance = returnAttribute($s, ['instance'], null);
                parseAndCreate(ImagingStudySeriesSpecimen::class, $specimen, 'returnReference', $seriesFk);
                parseAndCreate(ImagingStudySeriesPerformer::class, $performer, 'returnImagingPerformer', $seriesFk);
                parseAndCreate(ImagingStudySeriesInstance::class, $instance, 'returnImageStudyInstance', $seriesFk);
            }
        }
    }
}
