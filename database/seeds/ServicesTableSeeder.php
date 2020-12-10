<?php

use App\Service;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        $services = [
            'Wi-Fi',
            'parcheggio',
            'idromassaggio',
            'riscaldamento',
            'arrotino',
            'aria condizionata'
        ];

        $descriptions = [
            'servizio Wi-Fi gratuito', 
            'parcheggio gratuito riservato', 
            'vasca da bagno con idromassaggio',
            'riscaldamento autonomo',
            'servizio di sveglia con arrotino',
            'dotato di aria condizionata'
        ];

        $icons = [
            'fas fa-wifi',
            'fas fa-parking',
            'fas fa-bath',
            'fas fa-temperature-high',
            'fas fa-bullhorn',
            'fas fa-snowflake',
        ];


        for($i = 0; $i < count($services); $i++){
            $newService = new Service;
            $newService->service = $services[$i];
            $newService->description = $descriptions[$i];
            $newService->icon = $icons[$i];
            $newService->save();
        }
    }
}

