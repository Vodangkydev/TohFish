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
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_transactions', 'order_id')) {
                $table->foreignId('order_id')->after('id')->constrained('orders')->onDelete('cascade');
            }
            if (!Schema::hasColumn('payment_transactions', 'payment_method')) {
                $table->string('payment_method')->after('order_id')->default('bank'); // bank, momo
            }
            if (!Schema::hasColumn('payment_transactions', 'amount')) {
                $table->unsignedBigInteger('amount')->after('payment_method')->default(0);
            }
            if (!Schema::hasColumn('payment_transactions', 'status')) {
                $table->string('status')->after('amount')->default('pending'); // pending, completed, expired, failed
            }
            if (!Schema::hasColumn('payment_transactions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('payment_transactions', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('payment_transactions', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('payment_transactions', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('payment_transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('payment_transactions', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
        });
    }
};
