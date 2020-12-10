<?php

namespace App\Http\Middleware;

use Closure;

class AuthKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('KEY');
        if($token == 'test'){
            return $next($request);
        }else{
            return response()->json(['messaggio'=>'chiave non ricosciuta']);
        }
    }
}
