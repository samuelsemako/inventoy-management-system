<?php

namespace App\Http\Controllers\v1\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Supplier;
use App\Models\Setup\SetupCounter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\SupplierResource;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fetchAllSupplier = SupplierResource::all();
        if ($fetchAllSupplier->isEmpty()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No Supplier Found',
                ],
                404
            );
        }
        return response()->json([
            'success' => true,
            'data' => $fetchAllSupplier,
           
        ], 200);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'supplier_name' => 'required|string|unique:suppliers,supplier_name',
            'email_address' => 'nullable|email',
            'phone_number' => 'nullable|string',
            'supplier_address' => 'nullable|string',
        ]);

        $SupplierId = SetupCounter::generateCustomId('SUPP');
        Supplier::create([
            'supplier_id' => $SupplierId,
            'supplier_name' => strtoupper($request->supplier_name),
            'email_address' => $request->email_address,
            'phone_number' => $request->phone_number,
            'supplier_address' => $request->supplier_address,
            'created_by' => $admin->admin_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier Created Successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new SupplierResource(Supplier::findorFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Auth::guard('admin')->user();
        $updateSupplier = Supplier::findOrFail($id);
        $request->validate([
            'supplier_name' => 'required|string|unique:suppliers,supplier_name,'.$updateSupplier->supplier_id.',supplier_id',
            'email_address' => 'nullable|email',
            'phone_number' => 'nullable|string',
            'supplier_address' => 'nullable|string',
        ]); 
        $updateSupplier->  update([
            'supplier_name' => strtoupper($request->supplier_name),
            'email_address' => $request->email_address,
            'phone_number' => $request->phone_number,
            'supplier_address' => $request->supplier_address,
            'updated_by' => $admin->admin_id,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Supplier Updated Successfully',
        ], 200);
    }
}
