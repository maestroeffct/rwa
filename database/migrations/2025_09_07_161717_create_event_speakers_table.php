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
        Schema::create('event_speakers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->integer('order')->unsigned()->nullable();
            $table->boolean('enable')->default(false);
            $table->bigInteger('created_at')->unsigned();

            $table->foreign('event_id')->on('events')->references('id')->cascadeOnDelete();
        });

        Schema::create('event_speaker_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_speaker_id')->unsigned();
            $table->string('locale', 191)->index();
            $table->string('name');
            $table->text('job')->nullable();
            $table->text('description')->nullable();

            $table->foreign('event_speaker_id')->on('event_speakers')->references('id')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_speakers');
        Schema::dropIfExists('event_speaker_translations');
    }
};
