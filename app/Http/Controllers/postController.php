<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;


class postController extends Controller
{

    public function index()
    {

        
        $post = Post::with('user')->latest()->get();
        return response([
            'post' => $post
        ], 200,[],JSON_NUMERIC_CHECK);
        
    }

    
    public function store(Request $request)
    {
        // Validar los datos entrantes
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            
            'images' => 'sometimes|array',
            'images.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:20000'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $post = Post::create([
                'uuid' => (string) Str::orderedUuid(),
                
               
                "user_id" => $request->user_id
            ]);

            $images = [];
            if($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = $image->getClientOriginalName();
                    $image->storeAs('post_folder/' . $post->id, $imageName);
                    $images[] = $imageName;
                }
                $post->update(['images' => json_encode($images)]);
            }

            return response()->json($post, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear la Post: '.$e->getMessage());
            return response()->json(['error' => 'Error al crear la Post'], 500);
        }
    }

















    
}
