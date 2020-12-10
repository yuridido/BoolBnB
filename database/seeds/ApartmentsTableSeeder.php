<?php

use App\Apartment;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class ApartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = User::all();
        for ($i = 0; $i <10; $i++){
            $newApartment = new Apartment;
            $newApartment->title = $faker->sentence($nbWords = 6, $variableNbWords = true);
            $newApartment->rooms = rand(1,10);
            $newApartment->beds = rand(1,20);
            $newApartment->bathrooms = rand(1,10);
            $newApartment->sm = rand(100,1000);
            $newApartment->address = $faker->streetAddress;
            $newApartment->latitude = $faker->latitude($min = -90, $max = 90);
            $newApartment->longitude = $faker->longitude($min = -180, $max = 180);
            $newApartment->city = $faker->city;
            $newApartment->postal_code = $faker->postcode;
            $newApartment->country = $faker->country;
            $newApartment->daily_price = rand(100,2000);
            $newApartment->description = $faker->text($maxNbChars = 200);
            $newApartment->user_id = $users->random()->id;
            $newApartment->save();
        }
    }
}
