<?php

namespace Database\Seeders;

use App\Models\Event;
use DateInterval;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 10; $i++) {
            $date = $faker->dateTime();
            $days = $faker->numberBetween(1, 7);
            Event::create([
                'valid_from' => $date,
                'valid_to' => $date->add(new DateInterval("P{$days}D")),
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'gps_lat' => $faker->randomFloat(8, max: 50),
                'gps_lng' => $faker->randomFloat(8, max: 50),
                'user_id' => $faker->numberBetween(1, 9)
            ]);
        }

    }
}
