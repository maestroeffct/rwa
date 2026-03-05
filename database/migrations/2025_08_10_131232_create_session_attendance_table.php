<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_attendance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('session_id')->unsigned();
            $table->enum('status', ['present', 'absent', 'late'])->default('present');
            $table->bigInteger('joined_at')->unsigned();
            $table->bigInteger('edited_at')->unsigned()->nullable();

            $table->foreign('student_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('session_id')->references('id')->on('sessions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_attendance');
    }
};
