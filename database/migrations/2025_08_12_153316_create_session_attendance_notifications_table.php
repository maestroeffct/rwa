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
        Schema::create('session_attendance_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('session_id')->unsigned()->comment("Notifications will be sent to sessions where the attendance feature is enabled after the session ends.");
            $table->bigInteger('notify_at')->unsigned();

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
        Schema::dropIfExists('session_attendance_notifications');
    }
};
