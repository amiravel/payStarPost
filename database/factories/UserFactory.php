<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_id' => Role::query()->whereTitle('normal user')->first()->id,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 12345,
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => Role::query()->whereTitle('admin')->first()->id,
            ];
        });
    }

    /**
     * @return UserFactory
     */
    public function authenticated()
    {
        return $this->state(function (array $attributes) {
            return [
                'client_id' => 123456789,
                'token' => 123456789
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * @return UserFactory
     */
    public function withPasswordConfirmation()
    {
        return $this->state(function (array $attribute){
            return [
                'password_confirmation' => $attribute['password']
            ];
        });
    }

    public function withoutEmail()
    {
        return $this->state(function (array $attribute){
            return [
                'email' => null
            ];
        });
    }

    public function withoutName()
    {
        return $this->state(function (array $attribute){
            return [
                'name' => null
            ];
        });
    }

    public function withoutPassword()
    {
        return $this->state(function (array $attribute){
            return [
                'password' => null
            ];
        });
    }

    public function withFourCharacterPassword()
    {
        return $this->state(function (array $attribute){
            return [
                'password' => 1234,
            ];
        });
    }

    public function withMoreThanTwentyCharacterPassword()
    {
        return $this->state(function (array $attribute){
            return [
                'password' => Str::random(25),
            ];
        });
    }
}
