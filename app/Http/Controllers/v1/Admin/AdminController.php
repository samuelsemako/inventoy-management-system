<?php

namespace App\Http\Controllers\v1\Admin;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Setup\SetupCounter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\AdminResource;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         
        $fetchAllAdmin = Admin:: all();
        if ($fetchAllAdmin->isEmpty()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No User Found',
                ],
                404
            );
        }
        return response()->json([
            'success' => true,
            'data' => $fetchAllAdmin,
           
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
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

        $adminId = SetupCounter::generateCustomId('ADMIN');
        Admin::create([
            'admin_id' => $adminId,
            'first_name' => strtoupper($request->firstName),
            'middle_name'   => strtoupper($request->middleName),
            'last_name'  => strtoupper($request->lastName),
            'phone_number' => $request->phoneNumber,
            'home_address' => $request->homeAddress,
            'title_id' => $request->titleId,
            'gender_id' => $request->genderId,
            'email_address' => strtolower($request->emailAddress),
            'password' => $adminId
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'User Created Successfully',
            ],
            201
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new AdminResource(Admin::findorFail($id));
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $updateAdmin = Admin::findOrFail($id);
        $request->validate([
            'firstName'     => ['sometimes', 'required', 'string', 'regex:/^[A-Za-z\s\'-]+$/', 'min:2', 'max:50'],
            'middleName'    => ['sometimes', 'nullable', 'string', 'regex:/^[A-Za-z\s\'-]+$/', 'min:2', 'max:50'],
            'lastName'      => ['sometimes', 'required', 'string', 'regex:/^[A-Za-z\s\'-]+$/', 'min:2', 'max:50'],
            'phoneNumber'   => ['sometimes', 'required', 'string', 'unique:admins,phone_number,' . $updateAdmin->admin_id . ',admin_id', 'regex:/^\+?[1-9]\d{1,14}$/'],
            'homeAddress'   => 'sometimes|required|string',
            'titleId'       =>  'sometimes|required|int|exists:setup_titles,title_id', 
            'genderId'      =>  'sometimes|required|int|exists:setup_genders,gender_id',
            'emailAddress'  =>  'sometimes|required|string|email|unique:admins,email_address,' . $updateAdmin->admin_id . ',admin_id',
        ]);

        $updateAdmin->update([
            'first_name'    => strtoupper($request->firstName) ?? $updateAdmin->first_name,
            'middle_name'   => strtoupper($request->middleName) ?? $updateAdmin->middle_name,
            'last_name'     => strtoupper($request->lastName) ?? $updateAdmin->last_name,
            'phone_number'  => $request->phoneNumber ?? $updateAdmin->phone_number,
            'home_address'  => $request->homeAddress ?? $updateAdmin->home_address,
            'title_id'      => $request->titleId ?? $updateAdmin->title_id,
            'gender_id'     => $request->genderId ?? $updateAdmin->gender_id,
            'status_id'     => $request->statusId ?? $updateAdmin->status_id,
            'email_address' => strtolower($request->emailAddress) ?? $updateAdmin->email_address,
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'User Updated Successfully',
            ],
            200
        );
}
}