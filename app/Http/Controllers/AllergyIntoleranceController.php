<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllergyIntoleranceRequest;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Models\AllergyIntolerance;
use App\Models\AllergyIntoleranceIdentifier;
use App\Models\AllergyIntoleranceNote;
use App\Models\AllergyIntoleranceReaction;
use App\Models\AllergyIntoleranceReactionManifestation;
use App\Models\AllergyIntoleranceReactionNote;
use App\Models\Resource;
use App\Models\ResourceContent;
use App\Services\FhirService;

class AllergyIntoleranceController extends Controller
{
    /**
     * Store a newly created AllergyIntolerance resource in storage.
     *
     * @param  \App\Http\Requests\AllergyIntoleranceRequest  $request
     * @param  \App\Services\FhirService  $fhirService
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAllergyIntolerance(AllergyIntoleranceRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            [$resource, $resourceKey] = $this->createResource('AllergyIntolerance');

            $allergyIntolerance = AllergyIntolerance::create(array_merge($resourceKey, $body['allergy_intolerance']));

            $allergyKey = ['allergy_id' => $allergyIntolerance->id];

            $this->createInstances(AllergyIntoleranceIdentifier::class, $allergyKey, $body, 'identifier');
            $this->createInstances(AllergyIntoleranceNote::class, $allergyKey, $body, 'note');

            if (isset($body['reaction']) && !empty($body['reaction'])) {
                $this->createNestedInstances(AllergyIntoleranceReaction::class, $allergyKey, $body, 'reaction', [
                    [
                        'model' => AllergyIntoleranceReactionManifestation::class,
                        'key' => 'allergy_react_id',
                        'bodyKey' => 'manifestation'
                    ],
                    [
                        'model' => AllergyIntoleranceReactionNote::class,
                        'key' => 'allergy_react_id',
                        'bodyKey' => 'note'
                    ],
                ]);
            }

            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);

            return response()->json($resource->allergyIntolerance->first(), 201);
        });
    }
}
