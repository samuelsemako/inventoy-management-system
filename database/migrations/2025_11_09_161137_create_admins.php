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
        Schema::create('admins', function (Blueprint $table) {
            $table->string('admin_id')->primary();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('phone_number', 25);
            $table->string('email_address')->unique();
            $table->string('home_address');
            $table->string('passport')->default('default.png')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->unsignedBigInteger('title_id');
            $table->unsignedBigInteger('gender_id');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('last_login')->nullable();
            $table->timestamps();

            $table->foreign('title_id')->references('title_id')->on('setup_titles')->onDelete('restrict')->onUpdated('cascade');
            $table->foreign('status_id')->references('status_id')->on('setup_statuses')->onDelete('restrict')->onUpdated('cascade');
            $table->foreign('gender_id')->references('gender_id')->on('setup_genders')->onDelete('restrict')->onUpdated('cascade');
        });
    }
    
    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
