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
        Schema::create('cvs', function (Blueprint $table) {
            $table->increments('cvs_id');
            $table->string('ho_ten');
            $table->string('age');
            $table->string('current_residence');
            $table->string('email');
            $table->string('level');
            $table->integer('willing_to_travel')->default(0);
            $table->boolean('sex');
            $table->string('place_of_birth');
            $table->string('phone');
            $table->text('url_facebook');
            $table->string('file_path');
            $table->integer('willing_to_work_overtime')->default(0);
            $table->text('previous_experiences')->nullable();
            $table->text('personal_experience')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cvs');
    }
};