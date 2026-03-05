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
        Schema::table('subscribes', function (Blueprint $table) {
            $table->enum('target_type', \App\Models\Subscribe::$targetTypes)->default('all')->after('id');
            $table->string('target')->nullable()->after('target_type');
        });


        Schema::create('subscribe_specification_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscribe_id')->unsigned();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('instructor_id')->unsigned()->nullable();
            $table->integer('course_id')->unsigned()->nullable();
            $table->integer('bundle_id')->unsigned()->nullable();


            $table->foreign('subscribe_id')->on('subscribes')->references('id')->cascadeOnDelete();
            $table->foreign('category_id')->on('categories')->references('id')->cascadeOnDelete();
            $table->foreign('instructor_id')->on('users')->references('id')->cascadeOnDelete();
            $table->foreign('course_id')->on('webinars')->references('id')->cascadeOnDelete();
            $table->foreign('bundle_id')->on('bundles')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribe_specification_items');
    }
};
