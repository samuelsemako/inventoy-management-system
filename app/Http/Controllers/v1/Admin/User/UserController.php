<?php

namespace App\Http\Controllers\v1\admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Setup\SetupCounter;
use App\Http\Controllers\Controller;
use App\Models\User\Customer;
use Illuminate\Support\Facades\Auth;
use App\Services\Cache\ClearCacheService;

class UserController extends Controller
{
 
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $user = Auth::guard('user')->user();
        $request->validate([
            'firstName'     => ['required', 'string', 'regex:/^[A-Za-z\s\'-]+$/', 'min:2', 'max:50'],
            'middleName'    => ['nullable', 'string', 'regex:/^[A-Za-z\s\'-]+$/', 'min:2', 'max:50'],
            'lastName'      => ['required', 'string', 'regex:/^[A-Za-z\s\'-]+$/', 'min:2', 'max:50'],
            'phoneNumber' => ['required', 'string', 'unique:admins,phone_number', 'regex:/^\+?[1-9]\d{1,14}$/',],
            'homeAddress' => 'required|string',
            'titleId' => 'required|int|exists:setup_titles,title_id',
            'genderId' => 'required|int|exists:setup_genders,gender_id',
            'emailAddress' => 'required|string|email|unique:admins,email_address',
        ]);

        $customerId = SetupCounter::generateCustomId('USER');
        Customer::create([
            'customer_id' => $customerId,
            'first_name' => strtoupper($request->firstName),
            'middle_name'   => strtoupper($request->middleName),
            'last_name'  => strtoupper($request->lastName),
            'phone_number' => $request->phoneNumber,
            'home_address' => $request->homeAddress,
            'title_id' => $request->titleId,
            'gender_id' => $request->genderId,
            'email_address' => strtolower($request->emailAddress),
            'password' => $customerId,
            'created_by' => $user->admin_id,
        ]);

        ClearCacheService::clearListCache('user_list');
        return response()->json(
            [
                'success' => true,
                'message' => 'User Created Successfully',
            ],
            201
        );
    }

  
    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }
}
