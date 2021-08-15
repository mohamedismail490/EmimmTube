<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Subscription;
use App\Models\User;
use Database\Factories\ChannelFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $user1 = User::factory()->create([
           'email' => 'e.m.i.m.m490@gmail.com',
        ]);

        $user2 = User::factory()->create([
            'email' => 'john@emimmtube.com',
        ]);

        $channel1 = Channel::factory()->create([
            'user_id' => $user1->id
        ]);

        $channel2 = Channel::factory()->create([
            'user_id' => $user2->id
        ]);

        $channel1->subscriptions()->create([
            'user_id' => $user2->id
        ]);

        $channel2->subscriptions()->create([
            'user_id' => $user1->id
        ]);

        Subscription::factory(10000)->create([
            'channel_id' => $channel1->id
        ]);

        Subscription::factory(10000)->create([
            'channel_id' => $channel2->id
        ]);
    }
}
