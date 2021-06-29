<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Posts\CreatePostRequest;
use App\Http\Requests\Api\Posts\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Exception;

class PostController extends Controller
{
    /**
     * @return PostCollection
     */
    public function index(): PostCollection
    {
        return new PostCollection(Post::orderBy('id', 'DESC')->paginate(10));
    }

    /**
     * @param $id
     * @return PostResource|JsonResponse
     */
    public function show($id)
    {
        $post = Post::find($id);
        if ($post === null) {
            return response()->json(['message' => 'Post not found!'], 404);
        }

        return new PostResource($post);
    }

    /**
     * @param CreatePostRequest $request
     * @return PostResource
     */
    public function store(CreatePostRequest $request): PostResource
    {
        $validateData = $request->validated();
        $validateData['author_id'] = (int)$request->user()->id;

        $post = Post::create($validateData);
        return new PostResource($post);
    }

    /**
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $post->update($request->validated());
        return new PostResource($post);
    }

    /**
     * @param Post $post
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json(['No content'], 204);
    }
}
