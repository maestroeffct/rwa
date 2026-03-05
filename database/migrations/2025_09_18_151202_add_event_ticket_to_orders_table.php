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
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('event_ticket_id')->unsigned()->nullable()->after('bundle_id');

            // Add event_ticket to type
            DB::statement("ALTER TABLE `sales` MODIFY COLUMN `type` enum('webinar', 'meeting', 'subscribe', 'promotion', 'registration_package', 'product', 'bundle', 'installment_payment', 'gift', 'event_ticket') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `payment_method`");
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('event_ticket_id')->unsigned()->nullable()->after('bundle_id');
            $table->integer('quantity')->unsigned()->nullable()->after('become_instructor_id');
        });

        Schema::table('accounting', function (Blueprint $table) {
            $table->integer('event_ticket_id')->unsigned()->nullable()->after('bundle_id');
        });

        Schema::table('user_commissions', function (Blueprint $table) {

            // Add events to source
            DB::statement("ALTER TABLE `user_commissions` MODIFY COLUMN `source` enum('courses', 'bundles', 'virtual_products', 'physical_products', 'meetings', 'events') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `user_group_id`");
        });
    }

};
