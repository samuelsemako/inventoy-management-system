<?php

namespace App\Http\Controllers\v1\Admin\Inventory;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Models\Inventory\Category;
use App\Models\Setup\SetupCounter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\CategoryResource;


class CategoryController extends Controller
{
    //Display a listing of the resource.
     
    public function index()
    {
        $fetchAllCategory = CategoryResource::collection(Category::all());
        if ($fetchAllCategory->isEmpty()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No Category Found',
                ],
                404
            );
        }
        return response()->json([
            'success' => true,
            'data' => $fetchAllCategory,
           
        ], 200);

    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $request ->validate([
            'category_name' => 'required|string|unique:categories,category_name',
        ]);

        $CategoryId = SetupCounter::generateCustomId('CATG');
        Category::create([
            'category_id' => $CategoryId,
            'category_name' => strtoupper($request->category_name),
            'created_by' => $admin->admin_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category Created Successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Auth::guard('admin')->user();
        $updateCategory = Category::findOrFail($id);
        $request ->validate([
            'category_name' => 'required|string|unique:categories,category_name,'.$updateCategory->category_id.',category_id',
        ]); 
        $updateCategory->update([
            'category_name' => strtoupper($request->category_name),
            'updated_by' => $admin->admin_id,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Category Updated Successfully',
        ], 200);
    }

    //Remove the specified resource from storage.
     
    public function destroy(string $id)
    {
        //
    }
}
