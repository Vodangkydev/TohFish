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
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_transactions', 'momo_request_id')) {
                $table->string('momo_request_id')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('payment_transactions', 'momo_trans_id')) {
                $table->string('momo_trans_id')->nullable()->after('momo_request_id');
            }
            if (!Schema::hasColumn('payment_transactions', 'momo_response_data')) {
                $table->text('momo_response_data')->nullable()->after('momo_trans_id');
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
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn(['momo_request_id', 'momo_trans_id', 'momo_response_data']);
        });
    }
};
