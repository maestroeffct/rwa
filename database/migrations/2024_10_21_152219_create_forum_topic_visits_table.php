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
        Schema::create('forum_topic_visits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('forum_id')->unsigned()->nullable();
            $table->integer('topic_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('uid')->nullable();
            $table->bigInteger('created_at')->unsigned();

            $table->foreign('forum_id')->on('forums')->references('id')->cascadeOnDelete();
            $table->foreign('topic_id')->on('forum_topics')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_topic_visits');
    }
};
