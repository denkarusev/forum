<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Comments\CreateCommentRequest;
use App\Http\Requests\Api\Comments\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;

class PostCommentController extends Controller
{
    public function index(int $postId)
    {
        return CommentResource::collection(Comment::wherePostId($postId)->paginate(10));
    }

    public function store(CreateCommentRequest $request, int $postId)
    {
        if (!Post::find($postId)) {
            return response(['message' => 'Post not found!'], 404);
        }

        $validateData = $request->validated();
        $validateData['author_id'] = (int)$request->user()->id;
        $validateData['post_id'] = $postId;

        $comment = Comment::create($validateData);
        return new CommentResource($comment);
    }

    public function show(int $postId, int $id)
    {
        if ($comment = Comment::whereId($id)->where('post_id', $postId)->first()) {
            return new CommentResource($comment);
        } else {
            return response(['message' => 'Post or comment not found!'], 404);
        }
    }

    public function update(UpdateCommentRequest $request, int $postId, int $id)
    {
        if ($comment = Comment::whereId($id)->where('post_id', $postId)->first()) {
            $comment->update($request->validated());
            return new CommentResource($comment);
        } else {
            return response(['message' => 'Post or comment not found!'], 404);
        }
    }

    public function destroy(int $postId, int $id)
    {
        try {
            if ($comment = Comment::whereId($id)->where('post_id', $postId)->first()) {
                $comment->delete();
                return response()->noContent();
            } else {
                return response(['message' => 'Post or comment not found!'], 404);
            }
        } catch (\Exception $e) {
            return response(['message' => 'Comment doesn\'t belong to the post'], 400);
        }
    }
}
