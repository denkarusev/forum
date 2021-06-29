<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /**
     * Test store method
     */
    public function testStoreComment()
    {
        /** @var User $user */
        $user = Passport::actingAs(
            factory(User::class)->create()
        );

        /** @var Post $post */
        $post = factory(Post::class)->create();

        /** @var Comment $comment */
        $comment = factory(Comment::class)->make();

        $this
            ->json('POST', route('posts.comments.store', ['post' => $post->id]), $comment->toArray())
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'comment' => $comment->comment
                ]
            ]);

        $this
            ->json('POST', route('posts.comments.store', ['post' => 99999]), $comment->toArray())
            ->assertStatus(404);
    }

    /**
     * Test show method
     */
    public function testShowComment()
    {
        /** @var Comment $comment */
        $comment = factory(Comment::class)->create();

        /** @var Post $post */
        $post = factory(Post::class)->create();
        $post->comment()->save($comment);

        $this
            ->json('GET', route('posts.comments.show', ['post' => $post->id, 'comment' => $comment->id]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'comment' => $comment->comment
                ]
            ]);

        $this
            ->json('GET', route('posts.comments.show', ['post' => 9999, 'comment' => 9999]))
            ->assertStatus(404);
    }

    /**
     * Test update method
     */
    public function testUpdateComment()
    {
        /** @var User $user */
        $user = Passport::actingAs(
            factory(User::class)->create(),
        );

        /** @var Comment $comment */
        $comment = factory(Comment::class)->create();

        /** @var Comment $editedComment */
        $editedComment = factory(Comment::class)->create();

        /** @var Post $post */
        $post = factory(Post::class)->create();
        $post->comment()->save($comment);

        $this
            ->json('PUT', route('posts.comments.update', ['post' => $post->id, 'comment' => $comment->id]), $editedComment->toArray())
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'comment' => $editedComment->comment
                ]
            ]);

        $this
            ->json('PUT', route('posts.comments.update', ['post' => 9999, 'comment' => 9999]), $editedComment->toArray())
            ->assertStatus(404);

    }

    /**
     * Test delete method
     */
    public function testDestroyComment()
    {
        /** @var User $user */
        $user = Passport::actingAs(
            factory(User::class)->create(),
        );

        /** @var Comment $comment */
        $comment = factory(Comment::class)->create();

        /** @var Post $post */
        $post = factory(Post::class)->create();
        $post->comment()->save($comment);

        $this
            ->json('DELETE', route('posts.comments.destroy', ['post' => $post->id, 'comment' => $comment->id]))
            ->assertStatus(204);

        $this
            ->json('DELETE', route('posts.comments.destroy', ['post' => 9999, 'comment' => 9999]))
            ->assertStatus(404);
    }
}
