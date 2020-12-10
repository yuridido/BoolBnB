<?php

use App\User;
use App\Role;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ruoli = ['admin', 'host'];
        for($i = 0; $i < count($ruoli); $i++){
            $newRole = new Role;
            $newRole->role = $ruoli[$i];
            $newRole->save();
        }
    }
}
