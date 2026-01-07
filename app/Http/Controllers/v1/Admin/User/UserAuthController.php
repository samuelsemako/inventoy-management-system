<?php

namespace App\Http\Controllers\v1\Admin\User;

use Illuminate\Http\Request;
use App\Models\User\Customer;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Container\Attributes\Auth;

class UserAuthController extends Controller
{
    function login(Request $request)
    {
        $request->validate([
            'emailAddress' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Customer::where('email_address', $request->emailAddress)->first();

        if ($user->status_id <> 1) {
            return response()->json([
                'success' => false,
                'message' => 'Account is suspended! Contact Admin for help.',
            ], 403);
        }
        
        if ($user && Hash::check($request->password, $user->password)) {
            $expiresAt = now()->addDay(1); // Token expires in 1 day
            $user->tokens()->delete(); // Invalidate previous tokens
            $token = $user->createToken('auth_token')->plainTextToken;// Create new token
            $user->tokens()->latest()->first()->update(['expires_at' => $expiresAt]);// Set expiration time

            $user->update(['last_login' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Login successfully',
                'token' => $token,
                ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }
    }


    public function fetchProfile(){
        $admin = new UserResource(Auth::guard('admin')->user());
        $staffData = Cache::remember("staff_profile_{$admin->admin_id}", now()->addmonth(), function () use ($admin) {
            return new AdminResource(
                Admin::with([
                    'title:title_id,title_name',
                    'gender:gender_id,gender_name',
                    'status:status_id,status_name'
                ])->findOrFail($admin->admin_id)
            );
        });
        return response()->json([
            'success' => true,
            'message' => 'Staff profile fetched successfully.',
            'data' => new AdminResource($staffData),
        ]);
    }

}
