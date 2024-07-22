<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Feed;
use App\Models\FeedImage;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $feeds = Feed::with('user', 'images')->get();
        return response(['feeds' => $feeds], 200, [], JSON_NUMERIC_CHECK);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([ 
                'content' => 'required',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para imágenes
            ]); 
    
            $feed = Feed::create([
                'content' => $request->content,
                'user_id' => $request->user_id,
            ]);
    
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('feed_images'); // Almacenar la imagen en storage/app/feed_images
                    $feed->images()->create(['image_path' => $path]);
                }
            }
    
            return response(['message' => 'success'], 201);
        } catch (\Exception $e) {
            return response(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $feed = Feed::find($id);
        if (!$feed) {
            return response(['message' => '404 Not found'], 404);
        }
        $feed->delete();
        return response(['message' => 'deleted'], 200);
    }
}
