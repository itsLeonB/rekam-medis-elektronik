<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use Illuminate\Http\Request;

class FhirController extends Controller
{
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

    public function createResource(string $resourceType, $satusehatId)
    {
        $resource = Resource::create([
            'res_type' => $resourceType,
            'res_ver' => 1,
            'satusehat_id' => $satusehatId
        ]);

        return $resource;
    }

    public function retrieveJsonPayload(Request $request)
    {
        $body = $request->all();

        $body = $this->removeEmptyValues($body);

        if ($body === null) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        return $body;
    }

    public function removeEmptyValues($array)
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                return !empty($this->removeEmptyValues($value));
            }
            return $value !== null && $value !== "";
        });
    }
}
