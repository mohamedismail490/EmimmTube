<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Comment::factory(75)->create();

        $comment = Comment::query()->latest('created_at')->first();

        Comment::factory(40)->create([
            'comment_id' => $comment->id
        ]);
    }
}
