<?php

use App\View;
use App\Apartment;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;


class ViewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $apartments = Apartment::all();
        for ($i = 0; $i < 1000; $i++) {
            $newView = new View;
            $newView->ip_guest = $faker->ipv4;
            $newView->created_at = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
            $newView->apartment_id = $apartments->random()->id;
            $newView->save();
        }
    }
}
