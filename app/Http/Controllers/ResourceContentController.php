<?php

namespace App\Http\Controllers;

use App\Models\ResourceContent;
use Illuminate\Http\Request;
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
