<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        News::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 10; $i++) {
            News::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'user_id' => $faker->numberBetween(1, 9)
            ]);
        }
    }
}
