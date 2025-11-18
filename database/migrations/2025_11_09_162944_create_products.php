<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //Run the migrations.
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('product_id')->primary();
            $table->string('product_name');
            $table->string('category_id');
            $table->string('product_description');
            $table->unsignedBigInteger('selling_price');// selling price
            $table->unsignedBigInteger('cost_price');//bought price
            $table->unsignedBigInteger('stock_quantity');//current quantity available
            $table->string('reordering_level');// when to reorder
            $table->string('supplier_id');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            

            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    //Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
