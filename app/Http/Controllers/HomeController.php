<?php

namespace App\Http\Controllers;
use App\Apartment;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apartment = Apartment::whereHas('sponsors',function($q){
            $q->where('end_sponsor','>=', Carbon::now());
        })->inRandomOrder()->get();

        return view('home', compact('apartment'));
    }

}



