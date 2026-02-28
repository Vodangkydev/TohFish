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
        Schema::create('job_details', function (Blueprint $table) {
            $table->increments('job_details_id');
            $table->unsignedInteger('job_id')->nullable();
            $table->string('vi_tri');
            $table->integer('total')->nullable();
            $table->string('workplace')->nullable();
            $table->string('work_address')->nullable();
            $table->text('job_description')->nullable();
            $table->dateTime('workday')->nullable();
            $table->string('business_hours')->nullable();
            $table->text('interest')->nullable();
            $table->text('request')->nullable();
            $table->string('age')->nullable();
            $table->string('level')->nullable();
            $table->string('profile_included')->nullable();
            $table->timestamps(); // Tạo cột created_at và updated_at

            // Thiết lập khóa ngoại
            $table->foreign('job_id')->references('job_id')->on('jobs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_details');
    }
};