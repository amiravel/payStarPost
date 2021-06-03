<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $user = User::factory()
            ->withPasswordConfirmation()
            ->make();

        $response = $this->postJson(route('register'), $user->toArray())
            ->assertJson([
                'httpCode' => 200,
                'message' => 'OK',
                'hasError' => false,
                'data' => [
                    'role' => $user->role->title,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'role_id' => $user->role_id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function test_email_is_required()
    {
        $user = User::factory()->withoutEmail()
            ->withPasswordConfirmation()
            ->make();

        $this->postJson(route('register'), $user->toArray())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'email' => ['The email field is required.']
                ]
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'role_id' => $user->role_id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function test_password_is_required()
    {
        $user = User::factory()
            ->withoutPassword()
            ->make();

        $this->postJson(route('register'), $user->toArray())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'password' => ['The password field is required.']
                ]
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'role_id' => $user->role_id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function test_password_confirmation_is_required()
    {
        $user = User::factory()->make();

        $this->postJson(route('register'), $user->toArray())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'password' => ['The password confirmation does not match.']
                ]
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'role_id' => $user->role_id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function test_password_at_least_must_have_five_characters()
    {
        $user = User::factory()
            ->withFourCharacterPAssword()
            ->withPasswordConfirmation()
            ->make();

        $this->postJson(route('register'), $user->toArray())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'password' => ['The password must be at least 5 characters.']
                ]
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'role_id' => $user->role_id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function test_password_cant_have_more_than_twenty_characters()
    {
        $user = User::factory()
            ->withMoreThanTwentyCharacterPassword()
            ->withPasswordConfirmation()
            ->make();

        $this->postJson(route('register'), $user->toArray())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'password' => ['The password must not be greater than 20 characters.']
                ]
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'role_id' => $user->role_id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function test_email_must_be_unique()
    {
        $firstUser = User::factory()->create();

        $secondUser = User::factory()->withPasswordConfirmation()
            ->make(['email' => $firstUser->email]);

        $this->postJson(route('register'), $secondUser->toArray())
            ->assertJson([
                'httpCode' => 400,
                'message' => 'Bad Request',
                'hasError' => true,
                'data' => [
                    'email' => ['The email has already been taken.']
                ]
            ]);
    }

}
