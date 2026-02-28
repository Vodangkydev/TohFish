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
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('job_id'); // Tạo cột id tự tăng
            $table->text('description');
            $table->boolean('status');
            $table->string('url_job_details');

            // Khóa ngoại liên kết với bảng categories
            $table->unsignedInteger('role_category_id')->nullable();
            $table->unsignedInteger('position_category_id')->nullable();
            $table->unsignedInteger('location_category_id')->nullable();

            $table->timestamps(); // Tạo cột created_at và updated_at

            // Thiết lập khóa ngoại
            $table->foreign('role_category_id')->references('category_id')->on('categories')->onDelete('set null');
            $table->foreign('position_category_id')->references('category_id')->on('categories')->onDelete('set null');
            $table->foreign('location_category_id')->references('category_id')->on('categories')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};