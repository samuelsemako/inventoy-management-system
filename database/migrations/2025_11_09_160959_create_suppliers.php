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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->string('supplier_id')->primary();
            $table->string('supplier_name');
            $table->string('phone_number', 25);
            $table->string('email_address')->unique();
            $table->string('supplier_address');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('status_id')->on('setup_statuses')->onDelete('restrict')->onUpdated('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
