<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_posts_list(): void
    {
        $this->signIn();

        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts?limit=10&offset=0');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authorized_user_can_create_post(): void
    {
        $user = $this->signIn();

        $postData = [
            'title' => 'Заголовок тестового поста',
            'text' => 'Текст тестового поста',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonPath('title', $postData['title'])
            ->assertJsonPath('user_id', $user->id);

        $this->assertDatabaseHas('posts', [
            'title' => 'Заголовок тестового поста',
            'user_id' => $user->id
        ]);
    }

    public function test_unauthorized_user_cannot_create_post(): void
    {
        $postData = [
            'title' => 'Заголовок',
            'text' => 'Текст',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(401);
    }

    public function test_can_get_only_my_posts(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Post::factory()->count(2)->create(['user_id' => $user->id]);
        Post::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/posts/my');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_store_post_validation_fails(): void
    {
        $this->signIn();

        $response = $this->postJson('/api/posts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'text']);
    }
}
