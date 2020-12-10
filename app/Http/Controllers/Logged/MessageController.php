<?php

namespace App\Http\Controllers\Logged;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // SELEZIONE DEI MESSAGGI DELL'UTENTE
        $messages = Message::select('*', 'messages.id as id_msg')
        ->join('apartments', 'messages.apartment_id', '=', 'apartments.id')
        ->where('apartments.user_id', '=', Auth::user()->id)
        ->orderBy('read', 'asc')
        ->get();
        return view('logged.messages',compact('messages'));
    }
    

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // trova messaggio con id passata in url 
       $message = Message::find($id);
       $read = !$message->read;
      
       // controllo se il messaggio e dell'utente loggato
       if($message->apartment->user_id  == Auth::id()){
           ///cerco messaggio e lo segno come letto/non letto con un update nella colonna read
              Message::where('id',$id)->update(['read'=> $read]);
              return redirect()->route('messages.index');
       }
       return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /// elimina messaggio con controllo su user loggato ////
    public function destroy(Message $message)
    {   
        if($message->apartment->user_id = Auth::id()) {
            $message->delete();
            return redirect()->back()->with('status', 'Hai eliminato il messaggio');

        } else {
            return redirect()->back()->with('status', 'messaggio non eliminato');
        }


    }
}
