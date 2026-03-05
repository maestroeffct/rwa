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
        Schema::create('discount_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('discount_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->integer('created_at')->unsigned();

            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        Schema::table('discounts', function (Blueprint $table) {
            DB::statement("ALTER TABLE `discounts` MODIFY COLUMN `source` enum('all', 'course', 'category', 'meeting', 'product', 'bundle', 'event') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `discount_type`");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_events');
    }
};
