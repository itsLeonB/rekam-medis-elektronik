<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Fhir\ResourceContent;
use Inertia\Inertia;

class ResourceContentController extends Controller
{
    public function index()
    {
        $resourceContents = ResourceContent::all();

        foreach ($resourceContents as $content) {
            $res_textJson = json_decode($content->res_text, true);

            $extractedResourceContents[] = [
                'id' => $content->id,
                'res_id' => $content->res_id,
                'res_ver' => $content->res_ver,
                'res_text' => $res_textJson['text']['div'],
            ];
        }

        return Inertia::render('Dashboard', [
             'resourceContents' => $extractedResourceContents
        ]);
    }
}
