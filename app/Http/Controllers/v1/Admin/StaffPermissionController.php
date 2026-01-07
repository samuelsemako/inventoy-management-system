<?php

namespace App\Http\Controllers\v1\Admin;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class StaffPermissionController extends Controller
{
    public function assignDirectPermissions(Request $request, $staffId)
    {
        $request->validate([
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $staff = Admin::findOrFail($staffId);
        
        $permissions = Permission::whereIn('id', $request->permissions)
            ->where('guard_name', 'admin')
            ->get();

        $staff->syncPermissions($permissions);

        return response()->json([
            'success' => true,
            'message' => 'Direct permissions assigned successfully.',
        ]);
    }


    public function revokeDirectPermissions(Request $request, $staffId)
    {
        $request->validate([
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $staff = Admin::findOrFail($staffId);

        $permissions = Permission::whereIn('id', $request->permissions)
            ->where('guard_name', 'admin')
            ->get();

        $staff->revokePermissionTo($permissions);

        return response()->json([
            'success' => true,
            'message' => 'Direct permissions revoked successfully.',
        ]);
    }
}