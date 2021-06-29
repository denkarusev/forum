<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /**
     * Test show method
     */
    public function testShowPost()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();

        $this
            ->json('GET', route('posts.show', ['post' => $post->id]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => $post->title,
                    'body'  => $post->body,
                ]
            ]);

        $this
            ->json('GET', route('posts.show', ['post' => 100]))
            ->assertStatus(404);
    }

    /**
     * Test store method
     */
    public function testStorePost()
    {
        /** @var User $user */
        $user = Passport::actingAs(
            factory(User::class)->create(),
        );

        /** @var Post $post */
        $post = factory(Post::class)->make();

        $this
            ->json('POST', route('posts.store'), $post->toArray())
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => $post->title,
                    'body'  => $post->body,
                ]
            ]);
    }

    /**
     * Test update method
     */
    public function testUpdatePost()
    {
        /** @var User $user */
        $user = Passport::actingAs(
            factory(User::class)->create(),
        );

        /** @var Post $post */
        $post = factory(Post::class)->create();

        /** @var Post $editedPost */
        $editedPost = factory(Post::class)->make();

        $this
            ->json('PUT', route('posts.update', ['post' => $post->id]), $editedPost->toArray())
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => $editedPost->title,
                    'body'  => $editedPost->body,
                ]
            ]);
    }

    /**
     * Test destroy method
     */
    public function testDeletePost()
    {
        /** @var User $user */
        $user = Passport::actingAs(
            factory(User::class)->create(),
        );

        /** @var Post $post */
        $post = factory(Post::class)->create();

        $this
            ->json('DELETE', route('posts.destroy', ['post' => $post->id]))
            ->assertStatus(204);
    }
}
