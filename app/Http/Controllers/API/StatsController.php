<?php

namespace App\Http\Controllers\API;
use App\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;



class StatsController extends Controller
{
    
    /**
     * Display the specified resource.
    * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Index(Request $request)
    {
       $id = $request->id;
       $response = [];
        $views =  DB::table('views')->selectRaw('DATE(views.created_at) as date,COUNT(DATE(views.created_at)) as daily_views')
            ->join('apartments', 'views.apartment_id', '=', 'apartments.id')
            ->where('apartment_id', '=', $id)
            ->groupBy('views.created_at')->get();
            $count = count($views);
            
        $messages =  DB::table('messages')->selectRaw('DATE(messages.created_at) as date_messages,COUNT(DATE(messages.created_at)) as daily_messages')
            ->join('apartments', 'messages.apartment_id', '=', 'apartments.id')
            ->where('apartment_id', '=', $id)
            ->groupBy('messages.created_at')->get();
            $count = count($views);
        $response['messages'] = $messages;
        $response['views'] = $views;
         
        return response()->json($response);
    }

    public function unreadMessages(Request $request)
    {
        $response = User::selectRaw('COUNT(messages.read) AS unread')
        ->join('apartments', 'users.id', '=', 'apartments.user_id')
        ->join('messages', 'apartments.id', '=', 'messages.apartment_id')
        ->where('users.id', '=', $request->id )
        // restituisce il numero dei messaggi non letti dell'utente
        ->where('messages.read', '=', '0' )
        ->groupBy('messages.read')
        ->get();

        return response()->json($response, 200);
    }



}

