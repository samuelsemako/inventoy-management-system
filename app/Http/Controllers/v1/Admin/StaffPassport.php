<?php

namespace App\Http\Controllers\v1\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin\Admin as AdminModel;


class StaffPassport extends Controller
{
    public function uploadPassport(Request $request, string $id)
    {
        $request->validate([
            'passport' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $admin = AdminModel::findOrFail($id);

        if (
            $admin->passport && $admin->passport !== AdminModel::DEFAULT_PASSPORT &&
            Storage::disk('public')->exists("passports/admin/{$admin->passport}")
        ) {
            Storage::disk('public')->delete("passports/admin/{$admin->passport}");
        }

        $file = $request->file('passport');
        $fileName = $admin->admin_id . Str::uuid() . '.' . $file->extension();

        // STORE THE FILE
        $file->storeAs('passports/admin', $fileName, 'public');

        $admin->passport = $fileName;
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Passport updated successfully',
            'passportUrl' => Storage::url('passports/admin/' . $fileName),
        ],);
    }
}
