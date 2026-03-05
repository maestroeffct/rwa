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
        Schema::create('theme_headers_footers', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['header', 'footer']);
            $table->string('component_name')->unique();
            $table->bigInteger('created_at')->unsigned();
        });

        Schema::create('theme_header_footer_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('theme_header_footer_id')->unsigned();
            $table->string('locale', 191)->index();
            $table->string('title');
            $table->longText('content');

            $table->foreign('theme_header_footer_id', 'theme_header_footer_id_trans')->on('theme_headers_footers')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_headers_footers');
        Schema::dropIfExists('theme_header_footer_translations');
    }
};
