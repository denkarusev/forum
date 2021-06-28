<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Posts\CreatePostRequest;
use App\Http\Requests\Api\Posts\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;

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
            return response(['message' => 'Post not found!'], 404);
        }

        return new PostResource($post);
    }

    public function store(CreatePostRequest $request)
    {
        $validateData = $request->validated();
        $validateData['author_id'] = (int)$request->user()->id;

        $post = Post::create($validateData);
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
        return response()->noContent();
    }

    public function comments(int $id)
    {
        $post = Post::find($id);
        if ($post === null) {
            return response(['message' => 'Post not found!'], 404);
        }

        return CommentResource::collection($post->comment);
    }
}
