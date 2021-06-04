<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'abstract' => $this->faker->paragraph,
            'text' => $this->faker->text
        ];
    }

    /**
     * @return PostFactory
     */
    public function withoutTitle()
    {
        return $this->state(function (array $attribute){
            return [
                'title' => null
            ];
        });
    }

    /**
     * @return PostFactory
     */
    public function withoutAbstract()
    {
        return $this->state(function (array $attribute){
            return [
                'abstract' => null
            ];
        });
    }

    /**
     * @return PostFactory
     */
    public function withoutText()
    {
        return $this->state(function (array $attribute){
            return [
                'text' => null
            ];
        });
    }


}
