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
        Schema::create('alerts', function (Blueprint $table) {
            $table->string('alert_id')->primary();
            $table->string('product_id');
            $table->string('alert_type'); // e.g., 'low_stock', 'expiry_warning'
            $table->text('message');
            $table->unsignedBigInteger('status_id')->default(10); // 'unread', 'read'
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('status_id')->references('status_id')->on('setup_statuses')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
