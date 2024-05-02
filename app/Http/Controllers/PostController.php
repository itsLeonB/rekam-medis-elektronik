<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class PostController extends Controller
{
    //
    public function show($slug)
    {
        $data = Post::where('slug', $slug)->first();
        if (!$data) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        return response()->json(['data' => $data], 200);
    }
    public function store(Request $request){
        $client = new Client(); //GuzzleHttp\Client
        $result = $client->post('your-request-uri', [
            'form_params' => [
                'sample-form-data' => 'value'
            ]
        ]);
        
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->slug = $request->slug;
        $post->save();
        return response()->json(["result" => "ok"], 201);

    }

}
