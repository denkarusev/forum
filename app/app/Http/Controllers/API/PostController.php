<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::orderBy('id','DESC')->paginate(10));
    }

    public function show($id)
    {
        $post = Post::find($id);
        if ($post === null) {
            abort(404, 'Post not found!');
        }

        return new PostResource($post);
    }
}
