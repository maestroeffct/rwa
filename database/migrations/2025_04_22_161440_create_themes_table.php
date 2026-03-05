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
        Schema::create('themes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('preview_image')->nullable();
            $table->integer('color_id')->unsigned()->nullable();
            $table->integer('font_id')->unsigned()->nullable();
            $table->integer('header_id')->unsigned()->nullable();
            $table->integer('footer_id')->unsigned()->nullable();
            $table->integer('home_landing_id')->unsigned()->nullable();
            $table->longText('contents')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('enable')->default(false);
            $table->bigInteger('created_at')->unsigned();


            $table->foreign('color_id')->references('id')->on('theme_colors_fonts')->cascadeOnDelete();
            $table->foreign('font_id')->references('id')->on('theme_colors_fonts')->cascadeOnDelete();
            $table->foreign('header_id')->references('id')->on('theme_headers_footers')->cascadeOnDelete();
            $table->foreign('footer_id')->references('id')->on('theme_headers_footers')->cascadeOnDelete();
            $table->foreign('home_landing_id')->references('id')->on('landings')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('themes');
    }
};
