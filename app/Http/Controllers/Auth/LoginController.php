<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    

    /**
     * Handle an incoming login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function showLoginForm()
    {
        return view('auth.login');
    }

     public function login(Request $request)
     {
         // Validation des données de connexion
         $request->validate([
             'email' => 'required|email',
             'password' => 'required',
         ]);
 
         // Tentative d'authentification de l'utilisateur
         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            /** @var \App\Models\User $user */
             $user = Auth::user();
             if(!$user){
                return response()->json(['error' => 'Unauthorized'], 401);
             }

             $token = $user->createToken('auth_token')->plainTextToken;


            
            return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
         }

             return response()->json(['error' => 'Unauthorized'], 401);
         // Si les identifiants sont incorrects
         
     }
     
     
        
 
     protected $redirectTo = '/users'; 


    /**
     * Logout the user and revoke their token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){}
    
}
