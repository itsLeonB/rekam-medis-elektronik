<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use Illuminate\Http\Request;

class FhirController extends Controller
{
    public function updateChildModels(object $parent, array $data, array $children)
    {
        foreach ($children as $child) {
            if (!empty($data[$child])) {
                foreach ($data[$child] as $c) {
                    $id = isset($c['id']) ? $c['id'] : null;
                    unset($c['id']);

                    $parent->$child()->updateOrCreate(
                        ['id' => $id],
                        $c
                    );
                }
            }
        }
    }

    public function createChildModels(object $parent, array $data, array $children)
    {
        foreach ($children as $c) {
            if (!empty($data[$c])) {
                $parent->$c()->createMany($data[$c]);
            }
        }
    }

    public function updateNestedInstances(object $parent, string $child, array $data, array $descendants)
    {
        if (!empty($data[$child])) {
            foreach ($data[$child] as $c) {
                $childData = $c[$child . '_data'];
                $id = isset($childData['id']) ? $childData['id'] : null;
                unset($childData['id']);
                $childInstance = $parent->$child()->updateOrCreate(['id' => $id], $childData);
                $this->updateChildModels($childInstance, $c, $descendants);
            }
        }
    }


    public function updateResource(string $satusehat_id): Resource
    {
        $resource = Resource::where('satusehat_id', $satusehat_id)->firstOrFail();
        $resource->increment('res_version');
        $resource->refresh();
        return $resource;
    }

    public function createResourceContent($resourceClass, Resource $resource)
    {
        $resource->refresh();

        $resourceText = new $resourceClass($resource);
        return $resource->content()->create([
            'res_ver' => $resource->res_version,
            'res_text' => json_encode($resourceText),
        ]);
    }


    public function createResource(string $resourceType, string $satusehatId)
    {
        $resource = Resource::create([
            'res_type' => $resourceType,
            'res_ver' => 1,
            'satusehat_id' => $satusehatId
        ]);

        return $resource;
    }

    /**
     * Retrieve the JSON payload from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function retrieveJsonPayload(Request $request)
    {
        if (empty($request->getContent())) {
            return response()->json(['error' => 'Empty request body'], 400);
        }

        $body = json_decode($request->getContent(), true);

        if ($body === null) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        $body = $this->removeEmptyValues($body);

        return $body;
    }


    public function createNestedInstances(object $parent, string $child, array $data, array $descendants)
    {
        if (!empty($data[$child])) {
            foreach ($data[$child] as $dc) {
                $instance = $parent->$child()->create($dc[$child . '_data']);
                $this->createChildModels($instance, $dc, $descendants);
            }
        }
    }


    public function removeEmptyValues($array)
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                return !empty($this->removeEmptyValues($value));
            }
            return $value !== null && $value !== ""; //&& !(is_array($value) && empty($value));
        });
    }
}
