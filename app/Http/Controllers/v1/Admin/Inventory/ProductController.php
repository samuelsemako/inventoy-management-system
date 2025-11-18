<?php

namespace App\Http\Controllers\v1\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Setup\SetupCounter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'product_name' => 'required|string|unique:products,product_name',
            'category_id' => 'required|string|exists:categories,category_id',   
            'product_description' => 'required|string',
            'selling_price' => 'required|integer',
            'cost_price' => 'required|integer',
            'stock_quantity' => 'required|integer', 
            'reordering_level' => 'required|integer',
            'supplier_id' => 'required|string|exists:suppliers,supplier_id',
        ]);


        $ProductId = SetupCounter::generateCustomId('PROD');
        $admin = Auth::guard('admin')->user();
        Product::create([
            'product_id' => $ProductId,
            'product_name' => strtoupper($request->product_name),   
            'category_id' => $request->category_id,
            'product_description' => $request->product_description,
            'selling_price' => $request->selling_price,
            'cost_price' => $request->cost_price,
            'stock_quantity' => $request->stock_quantity, 
            'reordering_level' => $request->reordering_level,
            'supplier_id' => $request->supplier_id,
            'created_by' => $admin->admin_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product Created Successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

 
}
