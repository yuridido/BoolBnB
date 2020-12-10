<?php

use App\Message;
use App\Apartment;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $apartments = Apartment::all();
        for($i = 0; $i < 30; $i++){
            $newMessage = new Message;
            $newMessage->email = $faker->email;
            $newMessage->name = $faker->firstname;
            $newMessage->lastname = $faker->lastname;
            $newMessage->message = $faker->text($maxNbChars = 200);
            $newMessage->read = rand(0,1);
            $newMessage->apartment_id = $apartments->random()->id;
            $newMessage->created_at = $faker->dateTimeThisMonth($max = 'now', $timezone = null) ;
            $newMessage->save();
        }

    }
}
