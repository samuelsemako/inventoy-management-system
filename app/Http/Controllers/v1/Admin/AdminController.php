<?php

namespace App\Http\Controllers\v1\Admin;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Setup\SetupCounter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Admin\AdminResource;
use App\Services\Cache\ClearCacheService;

class AdminController extends Controller
{

    //   Display a listing of the resource.
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $cursor = $request->get('Cursor','frstPage');
        $cacheKey = "staff_list{$cursor}";

        $posts =  Cache::tags('staff_list')->flexible($cacheKey, [now()->addmonth(), null], function () use ($admin) { 
           return Admin::with([
                'title:title_id,title_name',
                'gender:gender_id,gender_name',
                'status:status_id,status_name'
            ])
            ->where('admin_id', '!=', $admin->admin_id)
            ->cursorPaginate(2);
        });

        if ($posts->isEmpty()) {
            return response()->json(
                [
                    'success'  => false,
                    'message' => 'No staff found'
                ],
                404
            );
        }
        return response()->json(
            [
                'success' => true,
                'data' => AdminResource::collection($posts),
                'pagination' => [
                    'nextPageUrl' => $posts->nextPageUrl(),
                ]
            ],
            200
        );
    }


    //Store a newly created resource in storage.
    public function store(Request $request): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
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
            'password' => $adminId,
            'created_by' => $admin->admin_id,
        ]);

        ClearCacheService::clearListCache('staff_list');
        return response()->json(
            [
                'success' => true,
                'message' => 'User Created Successfully',
            ],
            201
        );
    }

    //Display the specified resource.
    public function show(string $id)
    {
        $staffData = Cache::remember("staff_profile_{$id}", now()->addmonth(), function () use ($id) {
            return new AdminResource(
                Admin::with([
                    'title:title_id,title_name',
                    'gender:gender_id,gender_name',
                    'status:status_id,status_name'
                ])->findOrFail($id)
            );

        });
        return response()->json(
            [
                'success' => true,
                'message' => 'Staff Profile Retrieved Successfully',
                'data' => $staffData,
            ],
            200
        );
    }


    //Update the specified resource in storage.
    public function update(Request $request, string $id)
    {
        $admin = Auth::guard('admin')->user();
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
            'updated_by'    => $admin->admin_id,
        ]);
        ClearCacheService::clearListCache('staff_list');
        Cache::forget("staff_profile_{$id}");
        
        return response()->json(
            [
                'success' => true,
                'message' => 'User Updated Successfully',
            ],
            200
        );
    }
}
