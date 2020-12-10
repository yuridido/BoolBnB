<?php

namespace App\Http\Controllers;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class SendMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required',
            'name'=>'required',
            'lastname'=>'required',
            'message'=>'required',
            'apartment_id'=>'required',
       ]);
       if($validator->fails()){
           return response()->json($validator->messages());
       }
       
       Message::create($request->all());

       return redirect()->back()->with('status', 'messaggio inviato correttamente');
        
    }

   
}
