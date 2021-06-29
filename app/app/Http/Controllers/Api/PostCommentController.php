<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Comments\CreateCommentRequest;
use App\Http\Requests\Api\Comments\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostCommentController extends Controller
{
    /**
     * @param int $postId
     * @return AnonymousResourceCollection
     */
    public function index(int $postId): AnonymousResourceCollection
    {
        return CommentResource::collection(Comment::wherePostId($postId)->paginate(10));
    }

    /**
     * @param CreateCommentRequest $request
     * @param int $postId
     * @return CommentResource|JsonResponse
     */
    public function store(CreateCommentRequest $request, int $postId)
    {
        if (!Post::find($postId)) {
            return response()->json(['message' => 'Post not found!'], 404);
        }

        $validateData = $request->validated();
        $validateData['author_id'] = (int)$request->user()->id;
        $validateData['post_id'] = $postId;

        $comment = Comment::create($validateData);
        return new CommentResource($comment);
    }

    /**
     * @param int $postId
     * @param int $id
     * @return CommentResource|JsonResponse
     */
    public function show(int $postId, int $id)
    {
        if ($comment = Comment::whereId($id)->where('post_id', $postId)->first()) {
            return new CommentResource($comment);
        } else {
            return response()->json(['message' => 'Post or comment not found!'], 404);
        }
    }

    /**
     * @param UpdateCommentRequest $request
     * @param int $postId
     * @param int $id
     * @return CommentResource|JsonResponse
     */
    public function update(UpdateCommentRequest $request, int $postId, int $id)
    {
        if ($comment = Comment::whereId($id)->where('post_id', $postId)->first()) {
            $comment->update($request->validated());
            return new CommentResource($comment);
        } else {
            return response()->json(['message' => 'Post or comment not found!'], 404);
        }
    }

    /**
     * @param int $postId
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $postId, int $id): JsonResponse
    {
        try {
            if ($comment = Comment::whereId($id)->where('post_id', $postId)->first()) {
                $comment->delete();
                return response()->json(['No content'], 204);
            } else {
                return response()->json(['message' => 'Post or comment not found!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Comment doesn\'t belong to the post'], 400);
        }
    }
}
