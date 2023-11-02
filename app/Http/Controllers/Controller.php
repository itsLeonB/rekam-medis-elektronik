<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function retrieveJsonPayload(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        if ($body === null) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }
        $body = removeEmptyValues($body);
        return $body;
    }

    public function createInstances(string $model, array $key, array $body, string $bodyKey, array $nestedModels = [])
    {
        if (isset($body) && array_key_exists($bodyKey, $body)) {
            foreach ($body[$bodyKey] as $item) {
                // Convert array attributes to JSON
                foreach ($item as $attrKey => $attrValue) {
                    if (is_array($attrValue)) {
                        $item[$attrKey] = json_encode($attrValue);
                    }
                }
                try {
                    $instance = $model::create(array_merge($key, $item));
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
    }
}
