<?php

namespace App\Http\Controllers;
use App\Apartment;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     // form che salva i dati della ricerca se non e nella pagina ricerca
    public function store(Request $request)
    {
        $address = $request->address;
        $range = $request->range;
        // li rimanda alla pagina di ricerca che fa poi partire la chiamata api ///
        return view('search.search', compact('address','range'));
    }


    public function show($id)
    {
       $apartment = Apartment::find($id);
       if (empty($apartment)) {
           abort('404');
       }
       //se user ID dell'appartamento non corrisponde con quello loggato, ERROR 403

       return view('apartment', compact('apartment'));
    }

}
