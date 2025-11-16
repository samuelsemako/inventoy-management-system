<?php

namespace App\Http\Controllers\v1\Admin\Auth;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\AdminResource;

class AdminAuthController extends Controller
{
    function login(Request $request)
    {
        $request->validate([
            'emailAddress' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email_address', $request->emailAddress)->first();

        if ($admin->status_id <> 1) {
            return response()->json([
                'success' => false,
                'message' => 'Account is suspended! Contact Admin for help.',
            ], 403);
        }
        
        if ($admin && Hash::check($request->password, $admin->password)) {
            $expiresAt = now()->addDay(1); // Token expires in 1 day
            $admin->tokens()->delete(); // Invalidate previous tokens
            $token = $admin->createToken('auth_token')->plainTextToken;// Create new token
            $admin->tokens()->latest()->first()->update(['expires_at' => $expiresAt]);// Set expiration time

            $admin->update(['last_login' => now()]);

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

    public function resetPassword(Request $request)
    {
        $request->validate([
            'emailAddress' => 'required|email',
        ]);
        $admin = Admin::where('email_address', $request->emailAddress)->first();
       
        if ($admin){
           $otp = rand(100000, 999999);      
           $expiresAt = now()->addMinutes(10); // expires in 5 minutes
            DB::table('reset_password_tokens')->updateOrInsert(
                ['id' => $admin->admin_id],
                [
                    'token' => $otp,
                    'expires_at' => $expiresAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return response()->json([
                'message' => 'Password reset email sent.',
                'otp' => $otp,
                'expires_at' => $expiresAt,
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',

            ], 404);
        }

    }

    public function finishPasswordReset(Request $request)
    {
        $request->validate([
            'adminId' => 'required|string',
            'otp' => 'required|integer',
            'newPassword' => 'required|string|min:8|confirmed', 
        ]);

        $admin = Admin::where('admin_id', $request->adminId)->first();

        $resetToken = DB::table('reset_password_tokens')
            ->where('id', $admin->admin_id)
            ->where('token', $request->otp)
            ->first();

        if (!$resetToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        if ($resetToken->expires_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired'
            ], 400);
        }

        $admin->update(['password' => $request->newPassword]);
        DB::table('reset_password_tokens')->where('id', $admin->admin_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'adminId' => 'required|string',
        ]);

        $admin = Admin::where('admin_id', $request->adminId)->first();

        if ($admin) {
            $otp = rand(100000, 999999); 
            $expiresAt = now()->addMinutes(10); // expires in 10 minutes

            DB::table('reset_password_tokens')->updateOrInsert(
                ['id' => $admin->admin_id],
                [
                    'token' => $otp,
                    'expires_at' => $expiresAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'OTP resent successfully.',
            ], 200);
        }
    }

    public function fetchProfile(){
        $admin = new AdminResource(Auth::guard('admin')->user());
        return response()->json([
            'success' => true,
            'data' => $admin,
        ]);
    }
}
