<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller



{
function register(Request $request){
$request->validate([
'name'=>'required',
'email'=>'required|email',
'password' => ['required', 'confirmed', 'string', 'min:8'],
]);

$user =User::create([
    'name'=>$request->name,
    'email'=>$request->email,
    'password'=>Hash::make($request->password),

]);

$token = $user->createToken('auth_token')->plainTextToken;

return response()->json([
    'user' => $user,
    'token' => $token,
], 201);

}

}
