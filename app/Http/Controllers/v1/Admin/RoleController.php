<?php

namespace App\Http\Controllers\v1\admin;

use Psy\Util\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'roleName' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id'
        ]);

        Role::create([
            'name' => ucwords($request->roleName),
            'guard_name' => 'admin'
        ])->syncPermissions($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully'
        ],);
    }


    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
