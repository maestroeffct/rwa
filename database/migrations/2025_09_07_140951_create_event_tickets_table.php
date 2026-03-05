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
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->decimal('price')->nullable();
            $table->string('icon')->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->integer('point')->unsigned()->nullable();
            $table->integer('discount')->unsigned()->nullable();
            $table->bigInteger('discount_start_at')->unsigned()->nullable();
            $table->bigInteger('discount_end_at')->unsigned()->nullable();
            $table->integer('order')->unsigned()->nullable();
            $table->boolean('enable')->default(false);
            $table->bigInteger('created_at')->unsigned();

            $table->foreign('event_id')->on('events')->references('id')->cascadeOnDelete();
        });

        Schema::create('event_ticket_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_ticket_id')->unsigned();
            $table->string('locale', 191)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('options')->nullable();

            $table->foreign('event_ticket_id')->on('event_tickets')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_tickets');
        Schema::dropIfExists('event_ticket_translations');
    }
};
