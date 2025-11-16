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
        Schema::create('sales', function (Blueprint $table) {
            $table->string('sales_id')->primary();
            $table->string('customer_name')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('sold_by');//reference admin
            $table->date('sales_date');
            $table->unsignedBigInteger('payment_method_id'); 
            $table->timestamps();

            $table->foreign('payment_method_id')->references('payment_method_id')->on('setup_payment_methods')->onDelete('restrict')->onUpdated('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
