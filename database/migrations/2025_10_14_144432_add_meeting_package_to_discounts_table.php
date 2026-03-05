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
        Schema::create('discount_meeting_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('discount_id')->unsigned();
            $table->integer('meeting_package_id')->unsigned();
            $table->integer('created_at')->unsigned();

            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->foreign('meeting_package_id')->references('id')->on('meeting_packages')->onDelete('cascade');
        });

        Schema::table('discounts', function (Blueprint $table) {
            DB::statement("ALTER TABLE `discounts` MODIFY COLUMN `source` enum('all', 'course', 'category', 'meeting', 'product', 'bundle', 'event', 'meeting_package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `discount_type`");
        });
    }


};
