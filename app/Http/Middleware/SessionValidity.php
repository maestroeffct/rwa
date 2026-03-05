<?php
namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLoginHistory;
use Illuminate\Support\Facades\Session;




class SessionValidity
{
   public function handle($request, Closure $next)
   {
       if (Auth::check()) {
           $user = Auth::user();
           $sessionId = Session::getId();


           $sessionRecord = UserLoginHistory::query()
               ->where('user_id', $user->id)
               ->where('session_id', $sessionId)
               ->first();
          
           // If session record exists and has an end time, invalidate it
           if (is_null($sessionRecord) || $sessionRecord->session_end_at) {
               Auth::logout();
               $request->session()->invalidate();
               $request->session()->regenerateToken();
           }
       }


       return $next($request);
   }
}
