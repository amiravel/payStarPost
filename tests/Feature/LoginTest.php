<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create()->only(['email', 'password']);

        $this->postJson(route('login'), $user)
            ->assertJsonStructure([
                'httpCode',
                'message',
                'hasError',
                'data' => [
                    'token',
                    'client_id'
                ]
            ]);

    }

    public function test_email_is_required()
    {
        $user = User::factory()
            ->create()
            ->only(['password']);

        $this->postJson(route('login'), $user)
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'email' => ['The email field is required.']
                ]
            ]);

    }

    public function test_password_is_required()
    {
        $user = User::factory()
            ->create()
            ->only(['email']);

        $this->postJson(route('login'), $user)
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'password' => ['The password field is required.']
                ]
            ]);

    }

    public function test_email_must_exist()
    {
        $user = User::factory()
            ->create()
            ->only(['email', 'password']);

        $user['email'] = 'something@wrong.com';

        $this->postJson(route('login'), $user)
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'email' => ['The selected email is invalid.']
                ]
            ]);

    }

    public function test_password_must_be_at_least_five_characters()
    {
        $user = User::factory()
            ->withFourCharacterPassword()
            ->create()
            ->only(['email', 'password']);

        $this->postJson(route('login'), $user)
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'password' => ['The password must be at least 5 characters.']
                ]
            ]);

    }

}
