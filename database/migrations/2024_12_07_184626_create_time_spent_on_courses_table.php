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
        Schema::create('time_spent_on_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->enum('page', ['learning_page','show'])->default('learning_page');
            $table->bigInteger('entry_time')->unsigned()->nullable();
            $table->bigInteger('exit_time')->unsigned()->nullable();
            $table->bigInteger('seconds_spent')->unsigned()->nullable();

            $table->foreign('course_id')->references('id')->on('webinars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_spent_on_courses');
    }
};
