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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code')->nullable();
            // Thông tin người mua / nhận hàng
            $table->string('shipping_address')->nullable();
            $table->string('shipping_phone', 20)->nullable();

            // Đặt hàng hộ cho người thân
            $table->boolean('is_for_relative')->default(false); // true nếu đặt hộ
            $table->string('relative_name')->nullable();
            $table->string('relative_phone', 20)->nullable();
            $table->string('relative_address')->nullable();

            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('status')->default('pending'); // pending, completed, cancelled, ...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


