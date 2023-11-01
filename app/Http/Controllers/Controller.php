<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function createInstances($model, $key, $body, $bodyKey, $nestedModels = []) {
        if (isset($body) && array_key_exists($bodyKey, $body)) {
            foreach ($body[$bodyKey] as $item) {
                // Convert array attributes to JSON
                foreach ($item as $attrKey => $attrValue) {
                    if (is_array($attrValue)) {
                        $item[$attrKey] = json_encode($attrValue);
                    }
                }
                $instance = $model::create(array_merge($key, $item));
                foreach ($nestedModels as $nestedModel) {
                    if (isset($nestedModel['model'], $nestedModel['key'], $nestedModel['bodyKey']) && (is_array($item[$nestedModel['bodyKey']]) || is_object($item[$nestedModel['bodyKey']]))) {
                        foreach ($item[$nestedModel['bodyKey']] as $nestedItem) {
                            $nestedModel['model']::create(array_merge([$nestedModel['key'] => $instance->id], $nestedItem));
                        }
                    }
                }
            }
        }
    }
}
