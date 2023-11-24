<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class IdFhirResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = Storage::disk('example-id-fhir')->files();

        foreach ($files as $f) {
            $resText = Storage::disk('example-id-fhir')->get($f);
            list($resType, $satusehatId) = explode('-', $f, 2);
            list($satusehatId, $ext) = explode('.', $satusehatId, 2);

            $res = Resource::create(
                [
                    'satusehat_id' => $satusehatId,
                    'res_type' => $resType
                ]
            );

            $res->content()->create(
                [
                    'res_text' => $resText,
                    'res_ver' => 1
                ]
            );

            switch ($resType) {
                case 'Specimen':
                    $this->seedSpecimen($res, $resText);
                    break;
                case 'DiagnosticReport':
                    $this->seedDiagnosticReport($res, $resText);
                    break;
                case 'ImagingStudy':
                    $this->seedImagingStudy($res, $resText);
                    break;
                default:
                    break;
            }
        }
    }


    private function seedImagingStudy($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $series = returnAttribute($resourceContent, ['series']);

        $imagingStudyData = [
            'status' => returnAttribute($resourceContent, ['status']),
            'modality' => $this->returnMultiCoding(returnAttribute($resourceContent, ['modality'])),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'started' => returnAttribute($resourceContent, ['started']),
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'referrer' => returnAttribute($resourceContent, ['referrer', 'reference']),
            'interpreter' => $this->returnMultiReference(returnAttribute($resourceContent, ['interpreter'])),
            'endpoint' => $this->returnMultiReference(returnAttribute($resourceContent, ['endpoint'])),
            'series_num' => returnAttribute($resourceContent, ['numberOfSeries']),
            'instances_num' => returnAttribute($resourceContent, ['numberOfInstances']),
            'procedure_reference' => returnAttribute($resourceContent, ['procedureReference', 'reference']),
            'procedure_code' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['procedureCode'])),
            'reason_reference' => $this->returnMultiReference(returnAttribute($resourceContent, ['reasonReference'])),
            'description' => returnAttribute($resourceContent, ['description']),
        ];

        $imagingStudyData = removeEmptyValues($imagingStudyData);

        $imagingStudy = $resource->imagingStudy()->createQuietly($imagingStudyData);
        $imagingStudy->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $imagingStudy->reasonCode()->createManyQuietly($this->returnCodeableConcept(returnAttribute($resourceContent, ['reasonCode'])));
        $imagingStudy->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));

        if (!empty($series)) {
            foreach ($series as $s) {
                $imgSeries = $imagingStudy->series()->createQuietly([
                    'uid' => returnAttribute($s, ['uid']),
                    'number' => returnAttribute($s, ['number']),
                    'modality' => returnAttribute($s, ['modality', 'code']),
                    'description' => returnAttribute($s, ['description']),
                    'num_instances' => returnAttribute($s, ['numberOfInstances']),
                    'endpoint' => $this->returnMultiReference(returnAttribute($s, ['endpoint'])),
                    'body_site_system' => returnAttribute($s, ['bodySite', 'system']),
                    'body_site_code' => returnAttribute($s, ['bodySite', 'code']),
                    'body_site_display' => returnAttribute($s, ['bodySite', 'display']),
                    'laterality' => returnAttribute($s, ['laterality', 'code']),
                    'specimen' => $this->returnMultiReference(returnAttribute($s, ['specimen'])),
                    'started' => returnAttribute($s, ['started']),
                    'performer' => $this->returnSeriesPerformer(returnAttribute($s, ['performer'])),
                ]);
                $imgSeries->instance()->createManyQuietly($this->returnSeriesInstance(returnAttribute($s, ['instance'])));
            }
        }
    }


    private function returnSeriesInstance($instances): array
    {
        $instance = [];

        if (!empty($instances)) {
            foreach ($instances as $i) {
                $instance[] = [
                    'uid' => returnAttribute($i, ['uid']),
                    'sop_class' => returnAttribute($i, ['sopClass', 'code']),
                    'number' => returnAttribute($i, ['number']),
                    'title' => returnAttribute($i, ['title'])
                ];
            }
        }

        return $instance;
    }


    private function returnSeriesPerformer($performers): array
    {
        $performer = [];

        if (!empty($performers)) {
            foreach ($performers as $p) {
                $performer[] = [
                    'function' => returnAttribute($p, ['function', 'coding', 0, 'code']),
                    'actor' => returnAttribute($p, ['actor', 'reference'])
                ];
            }
        }

        return $performer;
    }


    private function returnMultiCoding($codings): array
    {
        $coding = [];

        if (!empty($codings)) {
            foreach ($codings as $c) {
                $coding[] = returnAttribute($c, ['code']);
            }
        }

        return $coding;
    }


    private function seedDiagnosticReport($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $diagnosticData = [
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'status' => returnAttribute($resourceContent, ['status']),
            'category' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['category'])),
            'code' => returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'effective' => returnVariableAttribute($resourceContent, 'effective', ['DateTime', 'Period']),
            'issued' => returnAttribute($resourceContent, ['issued']),
            'performer' => $this->returnMultiReference(returnAttribute($resourceContent, ['performer'])),
            'results_interpreter' => $this->returnMultiReference(returnAttribute($resourceContent, ['resultsInterpreter'])),
            'specimen' => $this->returnMultiReference(returnAttribute($resourceContent, ['specimen'])),
            'result' => $this->returnMultiReference(returnAttribute($resourceContent, ['result'])),
            'imaging_study' => $this->returnMultiReference(returnAttribute($resourceContent, ['imagingStudy'])),
            'conclusion' => returnAttribute($resourceContent, ['conclusion'])
        ];

        $diagnosticData = removeEmptyValues($diagnosticData);

        $diagnostic = $resource->diagnostic()->createQuietly($diagnosticData);
        $diagnostic->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $diagnostic->media()->createManyQuietly($this->returnMedia(returnAttribute($resourceContent, ['media'])));
        $diagnostic->conclusionCode()->createManyQuietly($this->returnCodeableConcept(returnAttribute($resourceContent, ['conclusionCode'])));
    }


    private function returnMedia($medias): array
    {
        $media = [];

        if (!empty($medias)) {
            foreach ($medias as $m) {
                $media[] = [
                    'comment' => returnAttribute($m, ['comment']),
                    'link' => returnAttribute($m, ['link'])
                ];
            }
        }

        return $media;
    }


    private function seedSpecimen($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $collections = returnAttribute($resourceContent, ['collection']);
        $containers = returnAttribute($resourceContent, ['container']);

        $specimenData = [
            'accession_identifier_system' => returnAttribute($resourceContent, ['accessionIdentifier', 'system']),
            'accession_identifier_use' => returnAttribute($resourceContent, ['accessionIdentifier', 'use']),
            'accession_identifier_value' => returnAttribute($resourceContent, ['accessionIdentifier', 'value']),
            'status' => returnAttribute($resourceContent, ['status'], 'entered-in-error'),
            'type_system' => returnAttribute($resourceContent, ['type', 'coding', 0, 'system']),
            'type_code' => returnAttribute($resourceContent, ['type', 'coding', 0, 'code']),
            'type_display' => returnAttribute($resourceContent, ['type', 'coding', 0, 'display']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'received_time' => returnAttribute($resourceContent, ['receivedTime']),
            'collection_collector' => returnAttribute($collections, ['collector', 'reference']),
            'collection_collected' => returnVariableAttribute($collections, 'collected', ['DateTime', 'Period']),
            'collection_duration_value' => returnAttribute($collections, ['duration', 'value']),
            'collection_duration_comparator' => returnAttribute($collections, ['duration', 'comparator']),
            'collection_duration_unit' => returnAttribute($collections, ['duration', 'unit']),
            'collection_duration_system' => returnAttribute($collections, ['duration', 'system']),
            'collection_duration_code' => returnAttribute($collections, ['duration', 'code']),
            'collection_quantity_value' => returnAttribute($collections, ['quantity', 'value']),
            'collection_quantity_unit' => returnAttribute($collections, ['quantity', 'unit']),
            'collection_quantity_system' => returnAttribute($collections, ['quantity', 'system']),
            'collection_quantity_code' => returnAttribute($collections, ['quantity', 'code']),
            'collection_method' => returnAttribute($collections, ['method', 'coding', 0, 'code']),
            'collection_body_site_system' => returnAttribute($collections, ['bodySite', 'coding', 0, 'system']),
            'collection_body_site_code' => returnAttribute($collections, ['bodySite', 'coding', 0, 'code']),
            'collection_body_site_display' => returnAttribute($collections, ['bodySite', 'coding', 0, 'display']),
            'collection_fasting_status' => returnVariableAttribute($collections, 'fastingStatus', ['CodeableConcept', 'Duration']),
        ];

        $specimen = $resource->specimen()->createQuietly($specimenData);
        $specimen->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $specimen->parent()->createManyQuietly($this->returnReference(returnAttribute($resourceContent, ['parent'])));
        $specimen->request()->createManyQuietly($this->returnReference(returnAttribute($resourceContent, ['request'])));
        $specimen->processing()->createManyQuietly($this->returnProcessing(returnAttribute($resourceContent, ['processing'])));

        if (!empty($containers)) {
            foreach ($containers as $c) {
                $container = $specimen->container()->createQuietly([
                    'description' => returnAttribute($c, ['description']),
                    'type' => returnAttribute($c, ['type', 'coding', 0, 'code']),
                    'capacity_value' => returnAttribute($c, ['capacity', 'value']),
                    'capacity_unit' => returnAttribute($c, ['capacity', 'unit']),
                    'capacity_system' => returnAttribute($c, ['capacity', 'system']),
                    'capacity_code' => returnAttribute($c, ['capacity', 'code']),
                    'specimen_quantity_value' => returnAttribute($c, ['specimenQuantity', 'value']),
                    'specimen_quantity_unit' => returnAttribute($c, ['specimenQuantity', 'unit']),
                    'specimen_quantity_system' => returnAttribute($c, ['specimenQuantity', 'system']),
                    'specimen_quantity_code' => returnAttribute($c, ['specimenQuantity', 'code']),
                    'additive' => returnAttribute($c, ['additive'])
                ]);
                $container->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($c, ['identifier'])));
            }
        }
        $specimen->condition()->createManyQuietly($this->returnCodeableConcept(returnAttribute($resourceContent, ['condition'])));
        $specimen->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));
    }


    private function returnMultiCodeableConcept($codeableConcepts): array
    {
        $codeableConcept = [];

        if (!empty($codeableConcepts)) {
            foreach ($codeableConcepts as $cc) {
                $codeableConcept[] = returnAttribute($cc, ['coding', 0, 'code']);
            }
        }

        return $codeableConcept;
    }


    private function returnMultiReference($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = returnAttribute($r, ['reference']);
            }
        }

        return $reference;
    }


    private function returnIdentifier($identifiers): array
    {
        $identifier = [];

        if (!empty($identifiers)) {
            foreach ($identifiers as $i) {
                $identifier[] = [
                    'system' => returnAttribute($i, ['system']),
                    'use' => returnAttribute($i, ['use']),
                    'value' => returnAttribute($i, ['value'])
                ];
            }
        }
        return $identifier;
    }


    private function returnReference($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = [
                    'reference' => returnAttribute($r, ['reference'])
                ];
            }
        }

        return $reference;
    }


    private function returnProcessing($processings): array
    {
        $processing = [];

        if (!empty($processings)) {
            foreach ($processings as $p) {
                $processing[] = [
                    'description' => returnAttribute($p, ['description']),
                    'procedure' => returnAttribute($p, ['procedure', 'coding', 0, 'code']),
                    'additive' => returnAttribute($p, ['additive']),
                    'time' => returnVariableAttribute($p, 'time', ['DateTime', 'Period'])
                ];
            }
        }

        return $processing;
    }


    private function returnCodeableConcept($codeableConcepts): array
    {
        $codeableConcept = [];

        if (!empty($codeableConcepts)) {
            foreach ($codeableConcepts as $cc) {
                $codeableConcept[] = [
                    'system' => returnAttribute($cc, ['coding', 0, 'system']),
                    'code' => returnAttribute($cc, ['coding', 0, 'code']),
                    'display' => returnAttribute($cc, ['coding', 0, 'display'])
                ];
            }
        }

        return $codeableConcept;
    }


    private function returnAnnotation($annotations): array
    {
        $annotation = [];

        if (!empty($annotations)) {
            foreach ($annotations as $a) {
                $annotation[] = [
                    'author' => returnVariableAttribute($a, 'author', ['String', 'Reference']),
                    'time' => returnAttribute($a, ['time']),
                    'text' => returnAttribute($a, ['text'])
                ];
            }
        }

        return $annotation;
    }
}
