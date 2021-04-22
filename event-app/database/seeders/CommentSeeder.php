<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::truncate();
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Comment::create([
                'nick_name' => $faker->sentence,
                'content' => $faker->paragraph,
                'user_id' => $faker->numberBetween(1, 9),
                'news_id' => $faker->numberBetween(1, 9)
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            Comment::create([
                'nick_name' => $faker->sentence,
                'content' => $faker->paragraph,
                'user_id' => $faker->numberBetween(1, 9),
                'events_id' => $faker->numberBetween(1, 9)
            ]);
        }
    }
}
