<?php

use App\Image;
use App\Apartment;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apartments = Apartment::all();
        for($i=0; $i<20; $i++){
            $newImage = new Image;
            $newImage->path = "https://loremflickr.com/600/400/home";
            $newImage->apartment_id = $apartments->random()->id;
            $newImage->save();
        }
    }
}
