<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllergyIntoleranceRequest;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Models\Resource;
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
    public function store(AllergyIntoleranceRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('AllergyIntolerance');
            $allergyIntolerance = $resource->allergyIntolerance()->create($body['allergy_intolerance']);
            $this->createInstances($allergyIntolerance, 'identifier', $body);
            $this->createInstances($allergyIntolerance, 'note', $body);
            $this->createNestedInstances($allergyIntolerance, 'reaction', $body, ['manifestation', 'note']);
            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);
            return response()->json($resource->allergyIntolerance->first(), 201);
        });
    }

    /**
     * Update an existing AllergyIntolerance resource.
     *
     * @param AllergyIntoleranceRequest $request The HTTP request instance.
     * @param FhirService $fhirService The FHIR service instance.
     * @return \Illuminate\Http\JsonResponse The HTTP response instance.
     */
    public function update(AllergyIntoleranceRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        // return $fhirService->insertData(function () use ($body) {
        $resource = Resource::find($body['allergy_intolerance']['resource_id']);

        $resource->res_version = $resource->res_version + 1;
        $resourceVersion = $resource->res_version;

        $allergyIntolerance = $resource->allergyIntolerance()->first();
        $allergyIntolerance->update($body['allergy_intolerance']);
        $allergyId = $allergyIntolerance->id;

        foreach ($body['identifier'] as $i) {
            $id = isset($i['id']) ? $i['id'] : null;

            $allergyIntolerance->identifier()->updateOrCreate(
                ['id' => $id],
                [
                    'allergy_id' => $allergyId,
                    'system' => $i['system'],
                    'use' => $i['use'],
                    'value' => $i['value']
                ]
            );
        }

        foreach ($body['note'] as $n) {
            $id = isset($n['id']) ? $n['id'] : null;

            $allergyIntolerance->note()->updateOrCreate(
                ['id' => $id],
                [
                    'allergy_id' => $allergyId,
                    'author' => $n['author'],
                    'time' => $n['time'],
                    'text' => $n['text']
                ]
            );
        }

        foreach ($body['reaction'] as $r) {
            $id = isset($r['reaction_data']['id']) ? $r['reaction_data']['id'] : null;
            unset($r['reaction_data']['id']);
            unset($r['reaction_data']['allergy_id']);

            $reaction = $allergyIntolerance->reaction()->updateOrCreate(
                ['id' => $id],
                array_merge(['allergy_id' => $allergyId], $r['reaction_data'])
            );

            $reactionId = $reaction->id;

            foreach ($r['manifestation'] as $rm) {
                $id = isset($rm['id']) ? $rm['id'] : null;
                unset($rm['id']);
                unset($rm['allergy_react_id']);

                $reaction->manifestation()->updateOrCreate(
                    ['id' => $id],
                    array_merge(['allergy_react_id' => $reactionId], $rm)
                );
            }

            foreach ($r['note'] as $rn) {
                $id = isset($rn['id']) ? $rn['id'] : null;
                unset($rn['id']);
                unset($rn['allergy_react_id']);

                $reaction->note()->updateOrCreate(
                    ['id' => $id],
                    array_merge(['allergy_react_id' => $reactionId], $rn)
                );
            }
        }

        $resource->refresh();

        $resourceText = new AllergyIntoleranceResource($resource);
        $resource->content()->create([
            'res_ver' => $resourceVersion,
            'res_text' => json_encode($resourceText),
        ]);

        return response()->json($resource->allergyIntolerance->first(), 201);
        // });
    }
}
