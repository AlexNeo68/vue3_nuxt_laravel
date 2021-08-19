<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;

class AuthController
{
    public function login(Request $request){
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();

            $scope = null;

//            А в банковских гарантиях будем проверять связанную модель для определения scope
//            там их будет 4 - клиент, агент, банк, админ


            if($user->isInfluencer()){
                $scope = 'influencer';
            }

            if($user->isAdmin()){
                $scope = 'admin';
            }

            $token = $user->createToken($scope, [$scope])->accessToken;

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



    public function user()
    {
        $user = Auth::user();

        $resource = new UserResource($user);

        if($user->is_influencer){
            return $resource;
        }
        return response($resource->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]), Response::HTTP_OK);
    }

    public function user_update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update($request->only(
            'first_name',
            'last_name',
            'email',
        ));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function user_password(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
