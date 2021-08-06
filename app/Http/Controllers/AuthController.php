<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();

            $token = $user->createToken('admin')->accessToken;
            $cookie = cookie('jwt', $token);
            return response([
                'token' => $token,
            ])->withCookie($cookie);
        }


       return response(['error'=>"Invalid Credentials"], Response::HTTP_UNAUTHORIZED);
    }

    public function logout() {

        $cookie = \Cookie::forget('jwt');

       return response([
           'message' => 'Success logout',
           'cookie' => $cookie
       ]);
    }

    public function register(RegisterRequest $request){
        $user = User::create($request->only(
            'first_name',
            'last_name',
            'email',
        ) + [
            'password' => bcrypt($request->password),
        ]);

        return response($user, Response::HTTP_CREATED);
       return response(['error'=>"Invalid Credentials"], Response::HTTP_UNAUTHORIZED);
    }
}
