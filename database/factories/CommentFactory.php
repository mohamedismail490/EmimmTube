<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Comment;
use App\Models\Vote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::query()->inRandomOrder()->first()->id;
            },
            'video_id' => 'fe3c8612-edc7-44ea-a9f1-e6826dacbd3b',
            'body' => $this->faker->sentence(8),
            'comment_id' => null
        ];
    }
}
