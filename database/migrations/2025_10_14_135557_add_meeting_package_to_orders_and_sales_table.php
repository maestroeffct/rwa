<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Cart */
        Schema::table('cart', function (Blueprint $table) {
            $table->integer('meeting_package_id')->unsigned()->nullable()->after('reserve_meeting_id');

            $table->foreign('meeting_package_id')->references('id')->on('meeting_packages')->onDelete('cascade');
        });

        /* User Commissions */
        Schema::table('user_commissions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `user_commissions` MODIFY COLUMN `source` enum('courses', 'bundles', 'virtual_products', 'physical_products', 'meetings', 'events', 'meeting_packages') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `user_group_id`");
        });

        /* Orders */
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('meeting_package_id')->unsigned()->nullable()->after('reserve_meeting_id');
        });

        /* Sale */
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('meeting_package_id')->unsigned()->nullable()->after('meeting_time_id');

            DB::statement("ALTER TABLE `sales` MODIFY COLUMN `type` enum('webinar', 'meeting', 'subscribe', 'promotion', 'registration_package', 'product', 'bundle', 'installment_payment', 'gift', 'event_ticket', 'meeting_package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `payment_method`");
        });

        /* Accounting */
        Schema::table('accounting', function (Blueprint $table) {
            $table->integer('meeting_package_id')->unsigned()->nullable()->after('meeting_time_id');
        });

    }
};
