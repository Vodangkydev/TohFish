<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration removes the foreign key constraint on product_id
     * to allow orders to be created even if products are deleted or don't exist.
     * Since we store all product information (name, price, image) in order_items,
     * we don't need a strict foreign key constraint.
     *
     * @return void
     */
    public function up()
    {
        // Find and drop the foreign key constraint using raw SQL
        // This is more reliable than trying to guess the constraint name
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'order_items' 
            AND COLUMN_NAME = 'product_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        foreach ($constraints as $constraint) {
            DB::statement("ALTER TABLE `order_items` DROP FOREIGN KEY `{$constraint->CONSTRAINT_NAME}`");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Re-add the foreign key constraint if rolling back
            $table->foreign('product_id')->references('images_id')->on('images')->onDelete('cascade');
        });
    }
};
