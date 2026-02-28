<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('product_id');
                $table->string('product_name');
                $table->unsignedBigInteger('product_price');
                $table->integer('quantity')->default(1);
                $table->string('product_image')->nullable();
                $table->timestamps();
                
                // Foreign keys
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                // Không có foreign key cho product_id vì sản phẩm có thể bị xóa
                
                // Unique constraint để mỗi user chỉ có 1 record cho mỗi product
                $table->unique(['user_id', 'product_id']);
                
                // Index để tìm nhanh giỏ hàng của user
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
