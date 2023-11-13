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

    /**
     * Create a new resource content.
     *
     * @param FhirResource $resourceClass The FHIR resource class.
     * @param Resource $resource The resource instance.
     * @return void
     */
    public function createResourceContent($resourceClass, Resource $resource)
    {
        $resourceData = new $resourceClass($resource);
        $resourceText = json_encode($resourceData);

        ResourceContent::create([
            'resource_id' => $resource->id,
            'res_ver' => 1,
            'res_text' => $resourceText,
        ]);
    }

    /**
     * Create a new resource of the given type.
     *
     * @param string $resourceType The type of the resource to create.
     * @return array An array containing the created resource and its key.
     */
    public function createResource(string $resourceType)
    {
        $resource = Resource::create([
            'res_type' => $resourceType,
            'res_ver' => 1,
        ]);

        $resourceKey = ['resource_id' => $resource->id];

        return [$resource, $resourceKey];
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

    /**
     * Create instances of a given model with the provided data.
     *
     * @param string $model The name of the model to create instances of.
     * @param array $key The key to use for creating the model instance.
     * @param array $body The data to use for creating the model instance.
     * @param string $bodyKey The key to use for accessing the data in the body.
     * @param array $nestedModels An optional array of nested models to create instances of.
     * @return void
     */
    public function createInstances(string $model, array $key, array $body, string $bodyKey, array $nestedModels = [])
    {
        if (isset($body) && array_key_exists($bodyKey, $body)) {
            foreach ($body[$bodyKey] as $item) {
                $item = $this->encodeArrayAttributesToJson($item);
                $instance = $this->createModelInstance($model, $key, $item);
                $this->createNestedModelInstances($nestedModels, $item, $instance);
            }
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
        foreach ($nestedModels as $nestedModel) {
            if (isset($nestedModel['model'], $nestedModel['key'], $nestedModel['bodyKey']) && (is_array($item[$nestedModel['bodyKey']]) || is_object($item[$nestedModel['bodyKey']]))) {
                foreach ($item[$nestedModel['bodyKey']] as $nestedItem) {
                    $nestedModel['model']::create(array_merge([$nestedModel['key'] => $instance->id], $nestedItem));
                }
            }
        }
    }
}
