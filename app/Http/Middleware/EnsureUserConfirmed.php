<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserConfirmed
{
    public function __construct()
    {
        $this->database = app('firebase.database');
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($this->getCurrentUserDisplay()==null){
            return $next($request);
        }
        else{
            $referenceUser = $this->database->getReference('/users/'.$this->getCurrentUserDisplay());
            $snapshotUser = $referenceUser->getSnapshot()->getValue();
            if ($snapshotUser["confirmed"]==true){
                return $next($request);
            }else{
                Auth::logout();
                return redirect(route("login"))->with(['uncomfirmed' => "Confirma tu correo para poder entrar a esta cuenta, comprueba tu bandeja de entrada."]);
            }
        }
    }
    public function getCurrentUserDisplay(){
        $auth = app('firebase.auth');
        if(Auth::id()!=null){
            return $auth->getUser(Auth::id())->displayName;
        }
        return null;
    }
}
