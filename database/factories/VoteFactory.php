<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Vote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::query()->where('id' ,'!=', '7d7e1d7e-df15-4853-81f4-d9465feae317')->inRandomOrder()->first()->id;
            },
            'type' => 'up',
            'voteable_id' => 'fe3c8612-edc7-44ea-a9f1-e6826dacbd3b',
            'voteable_type' => 'App\Models\Video'
        ];
    }
}
