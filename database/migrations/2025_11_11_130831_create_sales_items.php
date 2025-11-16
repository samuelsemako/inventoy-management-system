<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_items', function (Blueprint $table) {
            $table->string('sales_item_id')->primary();
            $table->string('sales_id');
            $table->string('product_id');
            $table->unsignedBigInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('sub_total', 10, 2); // unit 8 quanity
            $table->timestamps();


            $table->foreign('sales_id')->references('sales_id')->on('sales')->onDelete('restrict')->onUpdated('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('restrict')->onUpdated('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_items');
    }
};
