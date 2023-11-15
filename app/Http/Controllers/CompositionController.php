<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompositionRequest;
use App\Http\Resources\CompositionResource;
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
use App\Services\FhirService;

class CompositionController extends Controller
{
    public function postComposition(CompositionRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        // return $fhirService->insertData(function () use ($body) {
            [$resource, $resourceKey] = $this->createResource('Composition');

            $composition = Composition::create(array_merge($resourceKey, $body['composition']));

            $compositionKey = ['composition_id' => $composition->id];

            $this->createInstances(CompositionCategory::class, $compositionKey, $body, 'category');
            $this->createInstances(CompositionAuthor::class, $compositionKey, $body, 'author');
            $this->createInstances(CompositionAttester::class, $compositionKey, $body, 'attester');
            $this->createInstances(CompositionRelatesTo::class, $compositionKey, $body, 'relates_to');

            if (isset($body['event']) && !empty($body['event'])) {
                $this->createNestedInstances(CompositionEvent::class, $compositionKey, $body, 'event', [
                    [
                        'model' => CompositionEventCode::class,
                        'key' => 'composition_event_id',
                        'bodyKey' => 'code'
                    ],
                    [
                        'model' => CompositionEventDetail::class,
                        'key' => 'composition_event_id',
                        'bodyKey' => 'detail'
                    ],
                ]);
            }

            if (isset($body['section']) && !empty($body['section'])) {
                $this->createNestedInstances(CompositionSection::class, $compositionKey, $body, 'section', [
                    [
                        'model' => CompositionSectionAuthor::class,
                        'key' => 'composition_section_id',
                        'bodyKey' => 'author'
                    ],
                    [
                        'model' => CompositionSectionEntry::class,
                        'key' => 'composition_section_id',
                        'bodyKey' => 'entry'
                    ],
                ]);
            }

            $this->createResourceContent(CompositionResource::class, $resource);

            return response()->json($resource->composition->first(), 201);
        // });
    }
}
