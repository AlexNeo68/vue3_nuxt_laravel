<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        Gate::authorize('view', 'users');

        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {

        Gate::authorize('edit', 'users');

        $user = User::create($request->only(
            'first_name',
            'last_name',
            'email',
        ) + [
            'password' => bcrypt('123456'),
            'role_id' => 3,
        ]);

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Gate::authorize('view', 'users');
        return response(new UserResource($user), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('edit', 'users');
        $user->update($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
        ));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('edit', 'users');
        User::destroy($id);
        return response([], Response::HTTP_NO_CONTENT);
    }

    public function user()
    {
        $user = Auth::user();
        return response((new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
