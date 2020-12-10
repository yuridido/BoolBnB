<?php

use App\User;
use App\Role;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $roles = Role::all();
        for($i = 0; $i < 10; $i++){
            $newUser = new User;
            $newUser->name = $faker->firstname;
            $newUser->lastname = $faker->lastname;
            $newUser->email = $faker->email;
            $newUser->password = Hash::make('prova123');
            $newUser->date_of_birth = $faker->date;
            $newUser->role_id = 2;
            $newUser->save();
        }
    }
}
