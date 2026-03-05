<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndOrderIdToOfflinePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offline_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('offline_payments', 'type')) {
                $table->enum('type', ['charge', 'cart'])->default('charge')->after('status');
            }

            if (!Schema::hasColumn('offline_payments', 'order_id')) {
                $table->integer('order_id')->unsigned()->nullable()->after('type');
                $table->foreign('order_id')->on('orders')->references('id')->nullOnDelete();
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
        Schema::table('offline_payments', function (Blueprint $table) {
            if (Schema::hasColumn('offline_payments', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }

            if (Schema::hasColumn('offline_payments', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}


