<?php

namespace Database\Seeders;

use App\Models\Video;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class VoteDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Vote::factory(5000)->create([
            'type' => 'up'
        ]);

        Vote::factory(200)->create([
            'type' => 'down'
        ]);
    }
}
