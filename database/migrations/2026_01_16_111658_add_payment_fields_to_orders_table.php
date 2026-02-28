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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                if (Schema::hasColumn('orders', 'status')) {
                    $table->string('payment_method')->default('cash')->after('status'); // cash, bank, momo
                } else {
                    $table->string('payment_method')->default('cash');
                }
            }
            if (!Schema::hasColumn('orders', 'shipping_name')) {
                if (Schema::hasColumn('orders', 'user_id')) {
                    $table->string('shipping_name')->nullable()->after('user_id'); // Tên người nhận
                } else {
                    $table->string('shipping_name')->nullable();
                }
            }
            if (!Schema::hasColumn('orders', 'email')) {
                if (Schema::hasColumn('orders', 'shipping_address')) {
                    $table->string('email')->nullable()->after('shipping_address');
                } else {
                    $table->string('email')->nullable();
                }
            }
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('orders', 'district')) {
                $table->string('district')->nullable();
            }
            if (!Schema::hasColumn('orders', 'note')) {
                $table->text('note')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'shipping_name', 'email', 'city', 'district', 'note']);
        });
    }
};
