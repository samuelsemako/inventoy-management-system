<?php

namespace App\Http\Controllers\v1\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Category;
use App\Models\Setup\SetupCounter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;

class CategoryController extends Controller
{
    //Display a listing of the resource.
     
    public function index()
    {
        $fetchAllCategory = Category:: all();
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request ->validate([
            'category_name' => 'required|string|unique:categories,category_name',
        ]);

        $CategoryId = SetupCounter::generateCustomId('CATG');
        Category::create([
            'category_id' => $CategoryId,
            'category_name' => strtoupper($request->category_name),
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
