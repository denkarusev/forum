<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Posts\CreatePostRequest;
use App\Http\Requests\Api\Posts\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::orderBy('id', 'DESC')->paginate(10));
    }

    public function show($id)
    {
        $post = Post::find($id);
        if ($post === null) {
            abort(404, 'Post not found!');
        }

        return new PostResource($post);
    }

    public function store(CreatePostRequest $request)
    {
        $validateData = $request->validated();

        $post = Post::create([
            'title' => $validateData['title'],
            'body' => $validateData['body'],
            'author_id' => 3, // TODO: Получить user_id
        ]);

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
