<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RoleController
{

    public function index()
    {
        Gate::authorize('views', 'roles');
        return response(RoleResource::collection(Role::all()), Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        Gate::authorize('edit', 'roles');
        $role = Role::create($request->only('name'));
        if($permissions = $request->permissions){
            foreach ($permissions as $permission_id) {
                DB::table('role_permissions')->insert([
                    'role_id'=> $role->id,
                    'permission_id'=> $permission_id,
                ]);
            }
        }
        return response(new RoleResource($role), Response::HTTP_OK);
    }


    public function show(Role $role)
    {
        Gate::authorize('views', 'roles');
        return response(new RoleResource($role), Response::HTTP_OK);
    }

    public function update(Request $request, Role $role)
    {
        Gate::authorize('edit', 'roles');
        $role->update($request->only('name'));

        DB::table('role_permissions')->where('role_id', $role->id)->delete();

        if($permissions = $request->permissions){
            foreach ($permissions as $permission_id) {
                DB::table('role_permissions')->insert([
                    'role_id'=> $role->id,
                    'permission_id'=> $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Gate::authorize('edit', 'roles');
        DB::table('role_permissions')->where('role_id', $id)->delete();
        Role::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
