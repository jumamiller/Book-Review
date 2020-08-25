<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user=User::create([
            'name'  =>$request->name,
            'email' =>$request->email,
            'password'=>bcrypt($request->password),
        ]);

        $token=auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        $credentials=$request->only(['email','password']);

        if(!$token=auth()->attempt($credentials))
        {
            return response()->json(['error'=>'unauthorised'],401);
        }

        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        return repsonse()->json([
                'access_token'=>$token,
                'token_type'  =>'bearer',
                'expires_in'  =>auth()->factory()->getTTL()*60
            ]);
    }
}
