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
        Schema::table('cvs', function (Blueprint $table) {
            $table->dropColumn('sex');
        });
        
        Schema::table('cvs', function (Blueprint $table) {
            $table->string('sex', 10)->default('other')->after('age');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->dropColumn('sex');
        });
        
        Schema::table('cvs', function (Blueprint $table) {
            $table->boolean('sex')->after('age');
        });
    }
};
