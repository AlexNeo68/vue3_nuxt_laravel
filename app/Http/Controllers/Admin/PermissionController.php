<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController
{
    public function index(){
       return PermissionResource::collection(Permission::all());
    }
}
