<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_packages_sold', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meeting_package_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('sale_id')->unsigned();
            $table->integer('session_duration')->unsigned()->comment("Each Session duration (Minutes)");
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->bigInteger('paid_at')->unsigned();
            $table->bigInteger('expire_at')->unsigned();

            $table->foreign('meeting_package_id')->references('id')->on('meeting_packages')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('sale_id')->references('id')->on('sales')->cascadeOnDelete();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->integer('meeting_package_sold_id')->unsigned()->nullable()->after('event_id');
            $table->foreign('meeting_package_sold_id', 'meeting_package_sold_id')->references('id')->on('meeting_packages_sold')->cascadeOnDelete();

            DB::statement("ALTER TABLE `sessions` MODIFY COLUMN `date` int NULL DEFAULT NULL");
            DB::statement("ALTER TABLE `sessions` MODIFY COLUMN `status` enum('active', 'inactive', 'draft', 'finished') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active'");
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_packages_sold');
    }
};
