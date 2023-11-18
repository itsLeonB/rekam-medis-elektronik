<?php

namespace App\Http\Controllers;

use App\Http\Resources\FhirResource;
use App\Models\Resource;
use App\Models\ResourceContent;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function createResourceContent($resourceClass, Resource $resource)
    {
        $resource->refresh();

        $resourceText = new $resourceClass($resource);
        $resource->content()->create([
            'res_ver' => $resource->res_version,
            'res_text' => json_encode($resourceText),
        ]);
    }


    public function createResource(string $resourceType)
    {
        $resource = Resource::create([
            'res_type' => $resourceType,
            'res_ver' => 1,
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
        $body = json_decode($request->getContent(), true);

        if ($body === null) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        if (empty($request->getContent())) {
            return response()->json(['error' => 'Empty request body'], 400);
        }

        $body = removeEmptyValues($body);

        return $body;
    }


    public function createInstances(object $parent, string $child, array $data)
    {
        try {
            if (!empty($data[$child])) {
                $parent->$child()->createMany($data[$child]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Error dalam input data baru: ' . $e->getMessage()], 500);
        }
    }

    public function createNestedInstances(object $parent, string $child, array $data, array $descendants)
    {
        try {
            if (!empty($data[$child])) {
                foreach ($data[$child] as $dc) {
                    $instance = $parent->$child()->create($dc[$child . '_data']);

                    foreach ($descendants as $d) {
                        $this->createInstances($instance, $d, $dc);
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Error dalam input data baru: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Encodes array attributes to JSON format.
     *
     * @param array $item The array to be encoded.
     * @return array The encoded array.
     */
    public function encodeArrayAttributesToJson(array $item): array
    {
        foreach ($item as $attrKey => $attrValue) {
            if (is_array($attrValue)) {
                $item[$attrKey] = json_encode($attrValue);
            }
        }
        return $item;
    }

    /**
     * Create a new instance of a model and save it to the database.
     *
     * @param string $model The name of the model to create an instance of.
     * @param array $key An array of key-value pairs to set on the new model instance.
     * @param array $item An array of key-value pairs to set on the new model instance.
     * @return mixed The newly created model instance, or a JSON response with an error message if an exception occurs.
     */
    public function createModelInstance(string $model, array $key, array $item)
    {
        try {
            return $model::create(array_merge($key, $item));
        } catch (Exception $e) {
            return response()->json(['error' => 'Error dalam input data baru: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create nested model instances.
     *
     * @param array $nestedModels An array of nested models.
     * @param array $item The item to create nested models for.
     * @param mixed $instance The instance to associate the nested models with.
     * @return void
     */
    public function createNestedModelInstances(array $nestedModels, array $item, $instance)
    {
        try {
            foreach ($nestedModels as $nestedModel) {
                if (isset($nestedModel['model'], $nestedModel['key'], $nestedModel['bodyKey']) && (is_array($item[$nestedModel['bodyKey']]) || is_object($item[$nestedModel['bodyKey']]))) {
                    foreach ($item[$nestedModel['bodyKey']] as $nestedItem) {
                        $nestedModel['model']::create(array_merge([$nestedModel['key'] => $instance->id], $nestedItem));
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Error dalam input data baru: ' . $e->getMessage()], 500);
        }
    }
}
