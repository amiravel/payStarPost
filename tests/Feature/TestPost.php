<?php

namespace Tests\Feature;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestPost extends TestCase
{
    use RefreshDatabase;


    public function test_admin_can_store_posts()
    {
        User::factory()->admin()
            ->authenticated()
            ->create();

        $this->assertDatabaseCount('posts', 0);

        $post = Post::factory()->make();

        $this->postJson(route('posts.store'), $post->toArray(), $this->headers())
            ->assertJson([
                'httpCode' => 200,
                'message' => 'OK',
                'hasError' => false,
                'data' => [
                    'post_id' => 1
                ]
            ]);

        $this->assertDatabaseCount('posts', 1);

        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function test_title_is_required()
    {
        User::factory()->admin()
            ->authenticated()
            ->create();

        $post = Post::factory()->withoutTitle()->make();

        $this->postJson(route('posts.store'), $post->toArray(), $this->headers())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'title' => ['The title field is required.']
                ]
            ]);

        $this->assertDatabaseCount('posts', 0);

        $this->assertDatabaseMissing('posts', $post->toArray());
    }

    public function test_abstract_is_required()
    {
        User::factory()->admin()
            ->authenticated()
            ->create();

        $post = Post::factory()->withoutAbstract()->make();

        $this->postJson(route('posts.store'), $post->toArray(), $this->headers())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'abstract' => ['The abstract field is required.']
                ]
            ]);

        $this->assertDatabaseCount('posts', 0);

        $this->assertDatabaseMissing('posts', $post->toArray());
    }

    public function test_text_is_required()
    {
        User::factory()->admin()
            ->authenticated()
            ->create();

        $post = Post::factory()->withoutText()->make();

        $this->postJson(route('posts.store'), $post->toArray(), $this->headers())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'text' => ['The text field is required.']
                ]
            ]);

        $this->assertDatabaseCount('posts', 0);

        $this->assertDatabaseMissing('posts', $post->toArray());
    }

    public function test_authorized_user_can_see_posts()
    {
        User::factory()->admin()->authenticated()->create();

        $post = Post::factory()->create();

        $this->getJson(route('posts.index'), $this->headers())
            ->assertJson([
                'httpCode' => 200,
                'message' => 'OK',
                'hasError' => false,
                'data' => [
                    [
                        'id' => $post->id,
                        'title' => $post->title,
                        'abstract' => $post->abstract,
                        'text' => $post->text,
                    ]
                ]
            ]);
    }

    public function test_un_authorized_user_cant_see_posts()
    {
        $role = Role::factory()->create();

        $user = User::factory()->create([
            'role_id' => $role->id,
            'client_id' => 123456789,
            'token' => 123456789
        ]);

        $post = Post::factory()->create();

        $this->getJson(route('posts.index'), $this->headers())
            ->assertJson([
                'httpCode' => 403,
                'message' => 'unauthorized.',
                'hasError' => true,
            ]);
    }

    public function test_authorized_user_can_update_post()
    {
        User::factory()->admin()
            ->authenticated()
            ->create();

        $this->assertDatabaseCount('posts', 0);

        $post = Post::factory()->create();

        $oldTitle = $post->title;

        $post->title = 'title changed.';

        $this->patchJson(route('posts.update', $post), $post->toArray(), $this->headers())
            ->assertJson([
                'httpCode' => 200,
                'message' => 'OK',
                'hasError' => false,
                'data' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'abstract' => $post->abstract,
                    'text' => $post->text,
                ]
            ]);

        $this->assertDatabaseCount('posts', 1);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $post->title
        ]);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'title' => $oldTitle
        ]);
    }

    public function test_authorized_user_can_delete_post()
    {
        User::factory()->admin()
            ->authenticated()
            ->create();

        $this->assertDatabaseCount('posts', 0);

        $post = Post::factory()->create();

        $this->assertDatabaseCount('posts', 1);

        $this->deleteJson(route('posts.destroy', $post), [], $this->headers())
            ->assertJson([
                'httpCode' => 200,
                'message' => 'OK',
                'hasError' => false,
            ]);

        $this->assertDatabaseCount('posts', 0);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'title' => $post->title
        ]);
    }
}
