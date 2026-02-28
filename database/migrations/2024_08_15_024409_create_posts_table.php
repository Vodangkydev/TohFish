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
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('post_id');
            $table->boolean('role');
            $table->string('content');
            $table->string('description');
            $table->integer('view');
            $table->string('image_url');
            // Khóa ngoại liên kết với bảng categories
            $table->unsignedInteger('role_parent_id')->nullable();
            $table->unsignedInteger('position_parent_id')->nullable();
            $table->unsignedInteger('location_parent_id')->nullable();

            $table->timestamps(); // Tạo cột created_at và updated_at

            // Thiết lập khóa ngoại
            $table->foreign('role_parent_id')->references('parent_id')->on('parents')->onDelete('set null');
            $table->foreign('position_parent_id')->references('parent_id')->on('parents')->onDelete('set null');
            $table->foreign('location_parent_id')->references('parent_id')->on('parents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};