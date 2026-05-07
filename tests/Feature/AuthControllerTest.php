<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $userData = [
            'name' => 'Ivan Ivanov',
            'email' => 'ivan@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            'token'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ivan@example.com',
            'name' => 'Ivan Ivanov',
        ]);
    }

    public function test_registration_fails_if_passwords_do_not_match(): void
    {
        $userData = [
            'name' => 'Ivan Ivanov',
            'email' => 'ivan@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'different_password',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token'])
            ->assertJsonPath('user.email', 'test@example.com');
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
